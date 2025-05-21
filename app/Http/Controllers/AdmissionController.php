<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Course;
use App\Models\MiscFee;
use App\Models\ProgramCourseMapping;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\StudentCourse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdmissionController extends Controller
{
    public function updateInitialPayment(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $request->validate([
            'initial_payment' => 'required|numeric|min:0',
        ]);

        $billing->initial_payment = $request->input('initial_payment');

        // Recalculate balance due
        $billing->balance_due = $billing->total_assessment - $billing->initial_payment;

        // Divide balance equally
        $installment = $billing->balance_due / 4;

        // Store in DB
        $billing->prelims_due = $installment;
        $billing->midterms_due = $installment;
        $billing->prefinals_due = $installment;
        $billing->finals_due = $installment;

        $billing->save();

        return back()->with('success', 'Initial payment, balance due, and schedule updated.');
    }


    public function show($id)
    {
        $admission = Admission::with('courseMapping.program')->findOrFail($id);
        return view('registrar.enrollment.show', compact('admission'));
    }

    public function printCor($studentId)
    {
        // Get admission details with related program
        $admission = Admission::with('courseMapping.program', 'billing')->where('student_id', $studentId)->firstOrFail();
        $courseMappingId = $admission->course_mapping_id;

        $miscFees = MiscFee::where('program_course_mapping_id', $courseMappingId)->get();

        // Get enrolled course IDs for the student
        $studentCourses = StudentCourse::where('student_id', $studentId)->pluck('course_id');

        // Fetch full course details
        $courses = Course::whereIn('id', $studentCourses)->get();

        // Format course data for the COR
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

        // Get billing info from admission
        $billing = $admission->billing;

        return view('registrar.enrollment.print-cor', compact('admission', 'formattedCourses', 'miscFees', 'billing'));
    }



    public function index()
    {
        // Fetch all admissions with course mapping relationships
        $admissions = Admission::with([
            'courseMapping.program',
            'courseMapping.yearLevel',
            'courseMapping.semester',
            'billing' // <-- add this line
        ])->get();

        // Get unique and sorted course mappings
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        // Sample mapping ID just to pass along if needed
        $sampleMappingId = optional($courseMappings->first())->id;

        // Get all courses
        $allCourses = \App\Models\Course::orderBy('id')->get();

        // Get active scholarships
        $scholarships = Scholarship::where('status', 1)->orderBy('name')->get();

        // Get selected mapping ID from request
        $selectedMappingId = request('selected_mapping_id');

        // Default total units
        $totalUnits = 0;

        // If a mapping is selected, calculate total course units
        if ($selectedMappingId) {
            // Get the selected mapping
            $selectedMapping = ProgramCourseMapping::find($selectedMappingId);

            if ($selectedMapping) {
                // Find all mappings with same program, year, sem, and SY
                $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
                    ->where('year_level_id', $selectedMapping->year_level_id)
                    ->where('semester_id', $selectedMapping->semester_id)
                    ->where('effective_sy', $selectedMapping->effective_sy)
                    ->get();

                // Extract all course_ids
                $courseIds = $matchingMappings->pluck('course_id')->unique();

                // Sum course units from Course table
                $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
            }
        }

        // Pass all data to view
        return view('registrar.enrollment.enrollment', compact(
            'admissions',
            'courseMappings',
            'allCourses',
            'scholarships',
            'sampleMappingId',
            'selectedMappingId',
            'totalUnits'
        ));
    }


    public function store(Request $request)
    {
        Log::debug('Form submission data:', $request->all());

        if ($request->has('course_ids')) {
            Log::debug('Selected course IDs:', ['courses' => $request->course_ids]);
        }

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'school_year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'course_mapping_id' => 'nullable|exists:program_course_mappings,id',
            'scholarship' => 'nullable|string', // expecting scholarship id or 'none'
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        // Generate unique student ID (format: YY-XXX)
        do {
            $studentId = date('y') . '-' . rand(100, 999);
        } while (Admission::where('student_id', $studentId)->exists());

        // Create admission record
        $admission = Admission::create([
            'student_id' => $studentId,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'zip_code' => $request->zip_code,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'father_last_name' => $request->father_last_name,
            'father_first_name' => $request->father_first_name,
            'father_middle_name' => $request->father_middle_name,
            'father_contact' => $request->father_contact,
            'father_profession' => $request->father_profession,
            'father_industry' => $request->father_industry,
            'mother_last_name' => $request->mother_last_name,
            'mother_first_name' => $request->mother_first_name,
            'mother_middle_name' => $request->mother_middle_name,
            'mother_contact' => $request->mother_contact,
            'mother_profession' => $request->mother_profession,
            'mother_industry' => $request->mother_industry,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'birthplace' => $request->birthplace,
            'citizenship' => $request->citizenship,
            'religion' => $request->religion,
            'civil_status' => $request->civil_status,
            'course_mapping_id' => $request->course_mapping_id,
            'major' => $request->major,
            'admission_status' => $request->admission_status,
            'student_no' => $request->student_no,
            'admission_year' => $request->admission_year,
            'scholarship_id' => $request->scholarship !== 'none' ? $request->scholarship : null,
            'previous_school' => $request->previous_school,
            'previous_school_address' => $request->previous_school_address,
            'elementary_school' => $request->elementary_school,
            'elementary_address' => $request->elementary_address,
            'secondary_school' => $request->secondary_school,
            'secondary_address' => $request->secondary_address,
            'honors' => $request->honors,
            'school_year' => $activeSchoolYear ? $activeSchoolYear->name : $request->school_year,
            'semester' => $activeSchoolYear ? $activeSchoolYear->semester : $request->semester,
            'status' => 'Pending',
        ]);

        // Calculate tuition fee by summing course units from program_course_mappings + courses
        $mapping = ProgramCourseMapping::find($request->course_mapping_id);
        $totalUnits = 0;
        if ($mapping) {
            $relatedCourseIds = ProgramCourseMapping::where('program_id', $mapping->program_id)
                ->where('year_level_id', $mapping->year_level_id)
                ->where('semester_id', $mapping->semester_id)
                ->where(function ($query) use ($mapping) {
                    if ($mapping->effective_sy === null) {
                        $query->whereNull('effective_sy');
                    } else {
                        $query->where('effective_sy', $mapping->effective_sy);
                    }
                })
                ->pluck('course_id');

            $totalUnits = Course::whereIn('id', $relatedCourseIds)->sum('units');
        }

        $tuitionFee = $request->input('tuition_fee');  // This will be a numeric value submitted by the form

        // Calculate discount
        $discountValue = 0;
        $tuitionFeeDiscount = $tuitionFee;

        if ($request->scholarship !== 'none' && $request->scholarship !== null) {
            $scholarship = Scholarship::find($request->scholarship);
            if ($scholarship && $scholarship->discount) {
                // Discount is percentage
                $discountValue = $tuitionFee * ($scholarship->discount / 100);
                $tuitionFeeDiscount = $tuitionFee - $discountValue;
            }
        }

        // Calculate misc_fee from misc_fees table for the selected course_mapping_id
        $miscFee = 0;
        if ($request->course_mapping_id) {
            $miscFee = MiscFee::where('program_course_mapping_id', $request->course_mapping_id)->sum('amount');
        }

        // Initial payment and old accounts default to 0 or null, you can adjust
        $initialPayment = 0;
        $oldAccounts = 0;

        // Calculate total assessment
        $totalAssessment = $tuitionFeeDiscount + $miscFee + $oldAccounts;

        // Calculate balance due (assuming initial payment is zero for now)
        $balanceDue = $totalAssessment - $initialPayment;

        // Create billing record
        Billing::create([
            'student_id' => $admission->student_id,
            'school_year' => $admission->school_year,
            'semester' => $admission->semester,
            'tuition_fee' => $tuitionFee,
            'discount' => $discountValue,
            'tuition_fee_discount' => $tuitionFeeDiscount,
            'misc_fee' => $miscFee,
            'old_accounts' => $oldAccounts,
            'total_assessment' => $totalAssessment,
            'initial_payment' => $initialPayment,
            'balance_due' => $balanceDue,
            'is_full_payment' => false,
            'prelims_due' => null,
            'midterms_due' => null,
            'prefinals_due' => null,
            'finals_due' => null,
        ]);

        // Insert StudentCourses as before (optional, your existing logic)
        $isIrregular = in_array($request->admission_status, ['transferee', 'returnee']);

        if ($isIrregular && $request->has('course_ids')) {
            foreach ($request->course_ids as $courseId) {
                try {
                    StudentCourse::create([
                        'student_id' => $admission->student_id,
                        'course_id' => $courseId,
                        'school_year' => $admission->school_year,
                        'semester' => $admission->semester,
                        'status' => 'Pending',
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error inserting student course: ' . $e->getMessage());
                }
            }
        } else {
            $mapping = ProgramCourseMapping::find($request->course_mapping_id);

            $relatedMappings = ProgramCourseMapping::where('program_id', $mapping->program_id)
                ->where('year_level_id', $mapping->year_level_id)
                ->where('semester_id', $mapping->semester_id)
                ->where(function ($query) use ($mapping) {
                    if ($mapping->effective_sy === null) {
                        $query->whereNull('effective_sy');
                    } else {
                        $query->where('effective_sy', $mapping->effective_sy);
                    }
                })
                ->get();

            foreach ($relatedMappings as $map) {
                StudentCourse::create([
                    'student_id' => $admission->student_id,
                    'course_id' => $map->course_id,
                    'school_year' => $admission->school_year,
                    'semester' => $admission->semester,
                    'status' => 'Pending',
                ]);
            }
        }

        return redirect()->route('admissions.index')->with('success', 'Admission and billing created successfully!');
    }




    public function getTotalUnits(Request $request)
    {
        $mappingId = $request->input('mapping_id');
        $totalUnits = 0;
        $tuitionFee = 0;

        if ($mappingId) {
            $selectedMapping = ProgramCourseMapping::find($mappingId);

            if ($selectedMapping) {
                $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
                    ->where('year_level_id', $selectedMapping->year_level_id)
                    ->where('semester_id', $selectedMapping->semester_id)
                    ->where('effective_sy', $selectedMapping->effective_sy)
                    ->get();

                $courseIds = $matchingMappings->pluck('course_id')->unique();

                $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
            }
        }

        // Get active school year default_unit_price
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        if ($activeSchoolYear) {
            $tuitionFee = $totalUnits * $activeSchoolYear->default_unit_price;
        }

        return response()->json([
            'total_units' => $totalUnits,
            'tuition_fee' => $tuitionFee,
            'unit_price' => $activeSchoolYear->default_unit_price ?? 0
        ]);
    }

    public function getMappingUnits(Request $request)
    {
        $mappingId = $request->input('mapping_id');
        $totalUnits = 0;
        $tuitionFee = 0;
        $unitPrice = 0;

        if ($mappingId) {
            $selectedMapping = ProgramCourseMapping::find($mappingId);

            if ($selectedMapping) {
                $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
                    ->where('year_level_id', $selectedMapping->year_level_id)
                    ->where('semester_id', $selectedMapping->semester_id)
                    ->where('effective_sy', $selectedMapping->effective_sy)
                    ->get();

                $courseIds = $matchingMappings->pluck('course_id')->unique();

                $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
            }
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        if ($activeSchoolYear) {
            $unitPrice = $activeSchoolYear->default_unit_price ?? 0;
            $tuitionFee = $totalUnits * $unitPrice;
        }

        return response()->json([
            'total_units' => $totalUnits,
            'tuition_fee' => $tuitionFee,
            'unit_price' => $unitPrice,
        ]);
    }


    public function create()
    {
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel'])
            ->get()
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        $allCourses = \App\Models\Course::orderBy('code')->get();

        $scholarships = Scholarship::where('status', 1)->orderBy('name')->get(); // Only active ones

        return view('registrar.enrollment.enrollment', compact('courseMappings', 'allCourses', 'scholarships'));
    }
}
