<?php

namespace App\Http\Controllers;

use App\Models\RefRegion;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Billing;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MiscFee;
use App\Models\ProgramCourseMapping;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReEnrollRegularController extends Controller
{
public function search(Request $request)
{
    $query = $request->query('query');

    if (!$query) {
        return response()->json([], 400);
    }

    $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

    if (!$activeSchoolYear) {
  
        return response()->json(['error' => 'No active school year found'], 404);
    }

    $activeYear = trim($activeSchoolYear->name);
    $activeSemester = trim($activeSchoolYear->semester);

  

    $students = Admission::where('student_id', 'like', "%$query%")
        ->orWhere('first_name', 'like', "%$query%")
        ->orWhere('last_name', 'like', "%$query%")
        ->get();

    $result = [];

    foreach ($students as $student) {
        $studentId = trim($student->student_id);

        // Check if already enrolled in the active SY + semester (strict match)
        $alreadyEnrolled = \App\Models\Enrollment::where('student_id', $studentId)
            ->whereRaw('LOWER(TRIM(school_year)) = ?', [strtolower($activeYear)])
            ->whereRaw('LOWER(TRIM(semester)) = ?', [strtolower($activeSemester)])
            ->exists();


        // Check for failing grades
        $hasFailingGrades = \App\Models\StudentCourse::where('student_id', $studentId)
            ->where('grade_status', 'failed')
            ->exists();

        $result[] = [
            'student' => $student,
            'already_enrolled' => $alreadyEnrolled,
            'has_failing_grades' => $hasFailingGrades,
        ];
    }

    return response()->json($result);
}






    // In your controller
public function calculateTuitionFee(Request $request)
{
    try {
        // Get all course mappings with the same criteria
        $mappings = ProgramCourseMapping::where([
            'program_id' => $request->program_id,
            'year_level_id' => $request->year_level_id,
            'semester_id' => $request->semester_id,
            'effective_sy' => $request->effective_sy
        ])->get();

        // Get all course IDs from these mappings
        $courseIds = $mappings->pluck('course_id')->unique();

        // Fetch course records
        $courses = Course::whereIn('id', $courseIds)->get();

        $totalUnits = 0;

        foreach ($courses as $course) {
            $rawName = strtolower($course->code . ' ' . $course->name . ' ' . $course->description);
            $rawName = preg_replace('/- ?[a-z0-9 &()]+/', '', $rawName);

            $isNSTP = str_contains($rawName, 'national service training program') ||
                      str_contains($rawName, 'civic welfare training service') ||
                      str_contains($rawName, 'lts/cwts/rotc') ||
                      str_contains($rawName, 'lts/rotc');

            $units = floatval($course->units);

            if ($isNSTP) {
                $totalUnits += ($units / 2);  // divide NSTP units
            } else {
                $totalUnits += $units;
            }
        }

   
        $yearLevel = YearLevel::find($request->year_level_id);

        if ($yearLevel && strtolower($yearLevel->name) === '4th year') {

            $unitPrice = 504;
        } else {
  
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active school year found'
                ]);
            }

            $unitPrice = $activeSchoolYear->default_unit_price;
        }

        $tuitionFee = $totalUnits * $unitPrice;

        return response()->json([
            'success' => true,
            'total_units' => $totalUnits,
            'unit_price' => $unitPrice,
            'tuition_fee' => $tuitionFee
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

//store
public function submitForm(Request $request) 
{
    // ✅ Get the active school year and semester
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
    if (!$activeSchoolYear) {
        return redirect()->back()->with('error', 'No active school year found!');
    }

    // ✅ Check if student is already enrolled in current term
    $existingEnrollment = Enrollment::where('student_id', $request->student_id)
        ->where('school_year', $activeSchoolYear->name)
        ->where('semester', $activeSchoolYear->semester)
        ->exists();

    if ($existingEnrollment) {
        return redirect()->back()->with('error', 'Student is already enrolled in the active school year and semester.');
    }

    // ✅ Update course_mapping_id in admissions if needed
    $admission = Admission::where('student_id', $request->student_id)->first();
    if ($admission) {
        $admission->update([
            'course_mapping_id' => $request->course_mapping_id,
        ]);
    }

    // ✅ Create new enrollment
    $enrollment = Enrollment::create([
        'student_id' => $request->student_id,
        'school_year' => $activeSchoolYear->name,
        'semester' => $activeSchoolYear->semester,
        'course_mapping_id' => $request->course_mapping_id,
        'major' => $request->major,
        'status' => 'Pending',
        'enrollment_date' => now(),
        'enrollment_type' => 'old',
        'scholarship_id' => $request->scholarship !== 'none' ? $request->scholarship : null,
    ]);

    // ✅ Fetch related courses & calculate tuition (using same logic as calculateTuitionFee)
    $mapping = ProgramCourseMapping::findOrFail($request->course_mapping_id);
    $relatedCourseIds = ProgramCourseMapping::where('program_id', $mapping->program_id)
        ->where('year_level_id', $mapping->year_level_id)
        ->where('semester_id', $mapping->semester_id)
        ->where(function($query) use ($mapping) {
            $query->where('effective_sy', $mapping->effective_sy)
                  ->orWhereNull('effective_sy');
        })
        ->pluck('course_id');

    $courses = Course::whereIn('id', $relatedCourseIds)->get();
    
    $totalUnits = 0;
    foreach ($courses as $course) {
        $rawName = strtolower($course->code . ' ' . $course->name . ' ' . $course->description);
        $rawName = preg_replace('/- ?[a-z0-9 &()]+/', '', $rawName);

        $isNSTP = str_contains($rawName, 'national service training program') ||
                  str_contains($rawName, 'civic welfare training service') ||
                  str_contains($rawName, 'lts/cwts/rotc') ||
                  str_contains($rawName, 'lts/rotc');

        $units = floatval($course->units);

        if ($isNSTP) {
            $totalUnits += ($units / 2);  // divide NSTP units
        } else {
            $totalUnits += $units;
        }
    }

    // ✅ Check if year level is 4th year to set unit price to 504 (same as calculateTuitionFee)
    $yearLevel = YearLevel::find($mapping->year_level_id);
    $unitPrice = ($yearLevel && strtolower($yearLevel->name) === '4th year') 
        ? 504 
        : $activeSchoolYear->default_unit_price;

    $tuitionFee = $totalUnits * $unitPrice;

    // ✅ Scholarship and discounts
    $discountValue = 0;
    $tuitionFeeDiscount = $tuitionFee;
    $miscFee = MiscFee::where('program_course_mapping_id', $request->course_mapping_id)->sum('amount');
    $scholarshipId = null;

    if ($request->scholarship !== 'none' && $request->scholarship) {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship) {
            $scholarshipId = $scholarship->id;

            if (stripos($scholarship->name, 'Top Notcher') !== false) {
                // 100% discount
                $discountValue = $tuitionFee;
                $tuitionFeeDiscount = 0;
                $miscFee = 0;
            } elseif ($scholarship->discount) {
                $discountValue = $tuitionFee * ($scholarship->discount / 100);
                $tuitionFeeDiscount = $tuitionFee - $discountValue;
            }
        }
    }

    // ✅ Total assessment (tuition + misc fees)
    $totalAssessment = $tuitionFeeDiscount + $miscFee;

    // ✅ Get all unpaid balances from any semester EXCEPT current
    $oldBalance = Billing::where('student_id', $request->student_id)
        ->where(function($query) use ($activeSchoolYear) {
            $query->where('school_year', '!=', $activeSchoolYear->name)
                  ->orWhere('semester', '!=', $activeSchoolYear->semester);
        })
        ->where('balance_due', '>', 0)
        ->sum('balance_due');

    // ✅ New balance_due = current fees + unpaid balances
    $balanceDue = $totalAssessment + $oldBalance;

    // ✅ Create billing record (carry over old balance for reference)
    $billing = Billing::create([
        'student_id' => $request->student_id,
        'enrollment_id' => $enrollment->id,
        'school_year' => $activeSchoolYear->name,
        'semester' => $activeSchoolYear->semester,
        'tuition_fee' => $tuitionFee,
        'discount' => $discountValue,
        'tuition_fee_discount' => $tuitionFeeDiscount,
        'misc_fee' => $miscFee,
        'total_assessment' => $totalAssessment,
        'initial_payment' => 0,
        'balance_due' => $balanceDue,
        'old_accounts' => $oldBalance, // For display only
        'is_full_payment' => false,
        'scholarship_id' => $scholarshipId,
    ]);

    // ✅ Clear balance_due in ALL other semesters to avoid double-counting
    if ($oldBalance > 0) {
        Billing::where('student_id', $request->student_id)
            ->where(function($query) use ($activeSchoolYear) {
                $query->where('school_year', '!=', $activeSchoolYear->name)
                      ->orWhere('semester', '!=', $activeSchoolYear->semester);
            })
            ->where('balance_due', '>', 0)
            ->update([
                'balance_due' => 0,
                'old_accounts' => 0, // Clear old_accounts
                'remarks' => 'Balance transferred to SY ' . $activeSchoolYear->name . ' ' . $activeSchoolYear->semester
            ]);
    }

    // ✅ Assign student courses
    foreach ($courses as $course) {
        StudentCourse::create([
            'student_id' => $request->student_id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'school_year' => $activeSchoolYear->name,
            'semester' => $activeSchoolYear->semester,
            'status' => 'Enrolled',
            'units' => $course->units,
        ]);
    }

    return redirect()->back()->with('success', 'Student enrolled successfully! Billing and course records created.');
}



public function reprintCor($studentId)
{
    // Get the current active school year and semester
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

    if (!$activeSchoolYear) {
        return abort(404, 'No active school year found.');
    }

    // Find the enrollment for the student for the active school year and semester
    $enrollment = Enrollment::with('courseMapping.program', 'billing')
        ->where('student_id', $studentId)
        ->where('school_year', $activeSchoolYear->name)
        ->where('semester', $activeSchoolYear->semester)
        ->first();

    if (!$enrollment) {
        return abort(404, 'Student is not enrolled in the current active school year and semester.');
    }

    // Use the enrollment's course_mapping_id
    $courseMappingId = $enrollment->course_mapping_id;

    // Get misc fees for this program/course mapping
    $miscFees = MiscFee::where('program_course_mapping_id', $courseMappingId)->get();

    // Get enrolled courses for the student (across all terms or maybe just this enrollment?)
    // If you want only courses related to this enrollment, you might need additional filtering.
    $studentCourses = StudentCourse::where('student_id', $studentId)->pluck('course_id');

    // Fetch full course details
    $courses = Course::whereIn('id', $studentCourses)->get();

    // Format course data
    $formattedCourses = $courses->map(function ($course) {
        preg_match('/^([A-Z\s]+)?\s*([0-9]+)?$/i', $course->code, $matches);

        $subject = isset($matches[1]) ? trim($matches[1]) : '';
        $code = isset($matches[2]) ? $matches[2] : '';

        return [
            'subject' => $subject,
            'code' => $code,
            'name' => $course->name,
            'description' => $course->description,
            'units' => $course->units,
            'lecture_hours' => $course->lecture_hours,
            'lab_hours' => $course->lab_hours,
        ];
    });

    // Get billing related to this enrollment
    $billing = $enrollment->billing;

    return view('registrar.enrollment.print-cor', compact('enrollment', 'formattedCourses', 'miscFees', 'billing'));
}

public function editStudent($student_id)
{
    Log::info('Attempting to edit student', ['student_id' => $student_id]);

    // First try to find ANY admission for this student (without SY/semester filter)
    try {
        $admission = Admission::with('scholarship')
            ->where('student_id', $student_id)
            ->latest() // Get most recent admission
            ->firstOrFail();
            
        Log::debug('Found admission record', [
            'student_id' => $student_id,
            'admission_id' => $admission->id,
            'school_year' => $admission->school_year,
            'semester' => $admission->semester
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('No admission record found for student', ['student_id' => $student_id]);
        abort(404, 'Student record not found');
    }

    // Get active school year for context (but don't filter by it)
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
    $currentSchoolYear = $activeSchoolYear->name ?? null;
    $currentSemester = $activeSchoolYear->semester ?? null;

    $scholarships = Scholarship::all();
    
    // Initialize currentTuitionFee with a default value
    $currentTuitionFee = 0;
    
    // Get billing for the admission's school year/semester, not current
    $billing = Billing::where('student_id', $student_id)
        ->where('school_year', $admission->school_year)
        ->where('semester', $admission->semester)
        ->latest()
        ->first();
    
    if ($billing) {
        $currentTuitionFee = $billing->tuition_fee;
        Log::debug('Found billing record', [
            'billing_id' => $billing->id,
            'tuition_fee' => $currentTuitionFee
        ]);
    } else {
        Log::debug('No billing record found', ['student_id' => $student_id]);
    }

    // Get all course mappings with relationships
    $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
        ->get()
        ->groupBy(function ($item) {
            return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
        });

    // Get all regions for the dropdown
    $regions = RefRegion::all();

    Log::info('Successfully prepared data for student edit view', ['student_id' => $student_id]);

    return view('registrar.enrollment.reenrolledit-students', compact(
        'admission',
        'scholarships',
        'courseMappings',
        'regions',
        'currentTuitionFee',
        'currentSchoolYear',
        'currentSemester'
    ));
}
}
