<?php

namespace App\Http\Controllers;

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

        $activeYear = $activeSchoolYear->school_year;
        $activeSemester = $activeSchoolYear->semester;

        // Find matching students
        $students = Admission::where('student_id', 'like', "%$query%")
            ->orWhere('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->get();

        // Prepare array to hold enriched student data
        $result = [];

        foreach ($students as $student) {
            $studentId = $student->student_id;

            // 1. Check unpaid balance
            $billing = \App\Models\Billing::where('student_id', $studentId)->first();
            $hasUnpaidBalance = $billing && $billing->balance_due > 0;

            // 2. Check if already enrolled in active school year/semester
            $alreadyEnrolled = \App\Models\Enrollment::where('student_id', $studentId)
                ->where('school_year', $activeYear)
                ->where('semester', $activeSemester)
                ->exists();

            // 3. Check for failing grades in student_course
            $hasFailingGrades = \App\Models\StudentCourse::where('student_id', $studentId)
                ->where('grade_status', 'failed')  // adjust if your DB stores something else for fail
                ->exists();

            $result[] = [
                'student' => $student,
                'has_unpaid_balance' => $hasUnpaidBalance,
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

        // Calculate total units
        $totalUnits = Course::whereIn('id', $courseIds)->sum('units');

        // Get current active school year's unit price
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        
        if (!$activeSchoolYear) {
            return response()->json([
                'success' => false,
                'message' => 'No active school year found'
            ]);
        }

        $unitPrice = $activeSchoolYear->default_unit_price;
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

public function submitForm(Request $request)
{
    Log::debug('Form submission data:', $request->all());

  
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
    if (!$activeSchoolYear) {
        return redirect()->back()->with('error', 'No active school year found!');
    }


    $existingEnrollment = Enrollment::where('student_id', $request->student_id)
        ->where('school_year', $activeSchoolYear->name)
        ->where('semester', $activeSchoolYear->semester)
        ->exists();

    if ($existingEnrollment) {
        return redirect()->back()->with('error', 'Student is already enrolled in the active school year and semester.');
    }


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
    $totalUnits = $courses->sum('units');

    // 3. Calculate tuition fee with scholarship discount
    $unitPrice = $activeSchoolYear->default_unit_price;
    $baseTuitionFee = $totalUnits * $unitPrice;
    $discountValue = 0;
    $tuitionFeeDiscount = $baseTuitionFee;
    $scholarshipId = null;

    if ($request->scholarship !== 'none' && $request->scholarship) {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship) {
            $scholarshipId = $scholarship->id;
            if (stripos($scholarship->name, 'Full Scholarship') !== false) {
                $discountValue = $baseTuitionFee;
                $tuitionFeeDiscount = 0;
            } elseif ($scholarship->discount_percentage) {
                $discountValue = $baseTuitionFee * ($scholarship->discount_percentage / 100);
                $tuitionFeeDiscount = $baseTuitionFee - $discountValue;
            }
        }
    }

    // 4. Calculate misc fees
    $miscFee = MiscFee::where('program_course_mapping_id', $request->course_mapping_id)
                      ->sum('amount');

    $totalAssessment = $tuitionFeeDiscount + $miscFee;
    $balanceDue = $totalAssessment; // Initial balance is full amount

    // 5. Create Billing Record
    $billing = Billing::create([
        'student_id' => $request->student_id,
        'enrollment_id' => $enrollment->id,
        'school_year' => $activeSchoolYear->name,
        'semester' => $activeSchoolYear->semester,
        'tuition_fee' => $baseTuitionFee,
        'discount' => $discountValue,
        'tuition_fee_discount' => $tuitionFeeDiscount,
        'misc_fee' => $miscFee,
        'total_assessment' => $totalAssessment,
        'initial_payment' => 0, // Can be updated when payment is made
        'balance_due' => $balanceDue,
        'is_full_payment' => false,
        'scholarship_id' => $scholarshipId,
    ]);

    // 6. Enroll student in all courses for this mapping
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

}
