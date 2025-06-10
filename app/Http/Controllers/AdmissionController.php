<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Admission;
use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MiscFee;
use App\Models\ProgramCourseMapping;
use App\Models\RefRegion;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdmissionController extends Controller
{

  public function search(Request $request)
{
    $query = $request->input('query');
    
    $courses = Course::with('prerequisites')
        ->where('code', 'like', "%$query%")
        ->orWhere('name', 'like', "%$query%")
        ->select('id', 'code', 'name as title', 'units')
        ->limit(10)
        ->get()
        ->map(function($course) {
            $course->has_prerequisites = $course->prerequisites->isNotEmpty();
            return $course;
        });
    
    return response()->json($courses);
}
    public function update(Request $request, $student_id)
    {
        // Validate the request data
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'course_mapping_id' => 'nullable|exists:program_course_mappings,id',
            'scholarship' => 'nullable|string',

        ]);

        try {
            DB::transaction(function () use ($request, $student_id) {
                $user = Auth::user(); // This is the proper way to get the authenticated user

                if (!$user) {
                    throw new \Exception('No authenticated user found');
                }

                // 1. Update Admission Record
                $admission = Admission::where('student_id', $student_id)->firstOrFail();

                $updateData = $request->only([
                    'last_name',
                    'first_name',
                    'middle_name',
                    'address_line1',
                    'address_line2',
                    'region',
                    'province',
                    'city',
                    'barangay',
                    'zip_code',
                    'contact_number',
                    'email',
                    'father_last_name',
                    'father_first_name',
                    'father_middle_name',
                    'father_contact',
                    'father_profession',
                    'father_industry',
                    'mother_last_name',
                    'mother_first_name',
                    'mother_middle_name',
                    'mother_contact',
                    'mother_profession',
                    'mother_industry',
                    'gender',
                    'birthdate',
                    'birthplace',
                    'citizenship',
                    'religion',
                    'civil_status',
                    'course_mapping_id',
                    'major',
                    'admission_status',
                    'student_no',
                    'admission_year',
                    'previous_school',
                    'previous_school_address',
                    'elementary_school',
                    'elementary_address',
                    'secondary_school',
                    'secondary_address',
                    'honors',
                    'lrn',
                    'school_year',
                    'semester'
                ]);

                $admission->update($updateData);

                // 2. Update Enrollment Record
                $enrollment = Enrollment::where('student_id', $student_id)
                    ->where('school_year', $admission->school_year)
                    ->where('semester', $admission->semester)
                    ->first();

                if ($enrollment) {
                    $enrollment->update([
                        'course_mapping_id' => $request->course_mapping_id,
                        'scholarship_id' => ($request->scholarship && $request->scholarship !== 'none')
                            ? $request->scholarship
                            : null,
                    ]);
                }


                // 3. Update Billing Information if course mapping or scholarship changed
                if ($request->has('course_mapping_id') || $request->has('scholarship')) {
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

                    $tuitionFee = $request->input('tuition_fee');
                    $discountValue = 0;
                    $tuitionFeeDiscount = $tuitionFee;
                    $miscFee = 0;
                    $balanceDue = null;

                    if ($request->scholarship !== 'none' && $request->scholarship !== null) {
                        $scholarship = Scholarship::find($request->scholarship);

                        if ($scholarship) {
                            if (stripos($scholarship->name, 'Top Notcher') !== false) {
                                $discountValue = $tuitionFee;
                                $tuitionFeeDiscount = 0;
                                $miscFee = 0;
                                $balanceDue = 0;
                            } elseif ($scholarship->discount) {
                                $discountValue = $tuitionFee * ($scholarship->discount / 100);
                                $tuitionFeeDiscount = $tuitionFee - $discountValue;
                            }
                        }
                    }

                    if ($balanceDue === null && $request->course_mapping_id) {
                        $miscFee = MiscFee::where('program_course_mapping_id', $request->course_mapping_id)->sum('amount');
                    }

                    $billing = Billing::where('student_id', $student_id)
                        ->where('school_year', $admission->school_year)
                        ->where('semester', $admission->semester)
                        ->first();

                    // Get the existing initial payment if billing exists
                    $initialPayment = $billing ? $billing->initial_payment : 0;
                    $oldAccounts = $billing ? $billing->old_accounts : 0;

                    if ($balanceDue === null) {
                        $totalAssessment = $tuitionFeeDiscount + $miscFee + $oldAccounts;
                        $balanceDue = $totalAssessment - $initialPayment;

                        // Ensure balance doesn't go negative
                        if ($balanceDue < 0) {
                            // Option 1: Set balance to 0 and adjust initial payment
                            // $initialPayment = $totalAssessment;
                            // $balanceDue = 0;

                            // Option 2: Return with error (choose one approach)
                            throw new \Exception('Initial payment exceeds new program assessment');
                        }
                    }

                    if ($billing) {
                        // Capture the old values before update
                        $oldValues = $billing->only([
                            'tuition_fee',
                            'discount',
                            'tuition_fee_discount',
                            'misc_fee',
                            'total_assessment',
                            'balance_due',
                            'initial_payment',
                            'prelims_due',
                            'midterms_due',
                            'prefinals_due',
                            'finals_due'
                        ]);

                        $updateData = [
                            'tuition_fee' => $tuitionFee,
                            'discount' => $discountValue,
                            'tuition_fee_discount' => $tuitionFeeDiscount,
                            'misc_fee' => $miscFee,
                            'total_assessment' => $totalAssessment ?? 0,
                            'balance_due' => $balanceDue,
                            'initial_payment' => $initialPayment,
                        ];

                        // Recalculate installments if balance due changed
                        if ($balanceDue > 0) {
                            $installment = $balanceDue / 4;
                            $updateData['prelims_due'] = $installment;
                            $updateData['midterms_due'] = $installment;
                            $updateData['prefinals_due'] = $installment;
                            $updateData['finals_due'] = $installment;
                        } else {
                            $updateData['prelims_due'] = 0;
                            $updateData['midterms_due'] = 0;
                            $updateData['prefinals_due'] = 0;
                            $updateData['finals_due'] = 0;
                        }

                        $billing->update($updateData);

                        // Get the new values after update
                        $newValues = $billing->fresh()->only([
                            'tuition_fee',
                            'discount',
                            'tuition_fee_discount',
                            'misc_fee',
                            'total_assessment',
                            'balance_due',
                            'initial_payment',
                            'prelims_due',
                            'midterms_due',
                            'prefinals_due',
                            'finals_due'
                        ]);

                        // Calculate changes
                        $changes = [];
                        foreach ($oldValues as $key => $oldValue) {
                            if ($oldValue != $newValues[$key]) {
                                $changes[$key] = [
                                    'old' => $oldValue,
                                    'new' => $newValues[$key]
                                ];
                            }
                        }

                        // Log the billing update if there were changes
                        if (!empty($changes)) {
                            BillingHistory::create([
                                'billing_id' => $billing->id,
                                'user_id' => $user->id,
                                'action' => 'update',
                                'description' => 'Billing information updated (program change) by ' . $user->name,
                                'old_amount' => $oldValues['balance_due'],
                                'new_amount' => $newValues['balance_due'],
                                'changes' => $changes
                            ]);
                        }
                    } else {
                        // Create new billing if none exists
                        $billing = Billing::create([
                            'student_id' => $student_id,
                            'school_year' => $admission->school_year,
                            'semester' => $admission->semester,
                            'tuition_fee' => $tuitionFee,
                            'discount' => $discountValue,
                            'tuition_fee_discount' => $tuitionFeeDiscount,
                            'misc_fee' => $miscFee,
                            'total_assessment' => $totalAssessment ?? 0,
                            'initial_payment' => $initialPayment,
                            'balance_due' => $balanceDue,
                            'prelims_due' => $balanceDue > 0 ? $balanceDue / 4 : 0,
                            'midterms_due' => $balanceDue > 0 ? $balanceDue / 4 : 0,
                            'prefinals_due' => $balanceDue > 0 ? $balanceDue / 4 : 0,
                            'finals_due' => $balanceDue > 0 ? $balanceDue / 4 : 0,
                        ]);
                    }
                }

                // 4. Update Course Assignments if admission status or course mapping changed
                if ($request->has('admission_status') || $request->has('course_mapping_id')) {
                    // First delete existing course assignments for this semester
                    StudentCourse::where('student_id', $student_id)
                        ->where('school_year', $admission->school_year)
                        ->where('semester', $admission->semester)
                        ->delete();

                    $isIrregular = in_array($request->admission_status, ['transferee', 'returnee']);

                    if ($isIrregular && $request->has('course_ids')) {
                        foreach ($request->course_ids as $courseId) {
                            StudentCourse::create([
                                'student_id' => $student_id,
                                'course_id' => $courseId,
                                'school_year' => $admission->school_year,
                                'semester' => $admission->semester,
                                'status' => 'Pending',
                            ]);
                        }
                    } elseif ($request->course_mapping_id) {
                        $mapping = ProgramCourseMapping::find($request->course_mapping_id);

                        if ($mapping) {
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
                                    'student_id' => $student_id,
                                    'course_id' => $map->course_id,
                                    'school_year' => $admission->school_year,
                                    'semester' => $admission->semester,
                                    'status' => 'Pending',
                                ]);
                            }
                        }
                    }
                }
            });

            return redirect()->route('admissions.index')->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student. Please try again.');
        }
    }

    public function updateInitialPayment(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $request->validate([
            'initial_payment' => 'required|numeric|min:0',
        ]);

        $newInitialPayment = $request->input('initial_payment');

        // Check if initial payment exceeds total assessment
        if ($newInitialPayment > $billing->total_assessment) {
            return back()->with('error', 'Initial payment cannot be greater than the total assessment.');
        }

        // Calculate the difference between new and old initial payment
        $initialPaymentDifference = $newInitialPayment - $billing->initial_payment;

        // Check if the change would make balance due negative
        if ($billing->balance_due - $initialPaymentDifference < 0) {
            return back()->with('error', 'Initial payment cannot be greater than the remaining balance due.');
        }

        try {
            // Store previous values in case of error
            $previousInitialPayment = $billing->initial_payment;
            $previousBalanceDue = $billing->balance_due;

            // Update the initial payment
            $billing->initial_payment = $newInitialPayment;

            // Recalculate balance due by applying the difference
            $billing->balance_due = $previousBalanceDue - $initialPaymentDifference;

            // Only divide balance if there's still balance due
            if ($billing->balance_due > 0) {
                $installment = $billing->balance_due / 4;

                $billing->prelims_due = $installment;
                $billing->midterms_due = $installment;
                $billing->prefinals_due = $installment;
                $billing->finals_due = $installment;
            } else {
                // If balance is fully paid, set all installments to 0
                $billing->prelims_due = 0;
                $billing->midterms_due = 0;
                $billing->prefinals_due = 0;
                $billing->finals_due = 0;
                $billing->balance_due = 0; // Ensure balance is 0
            }

            $billing->save();

            return back()->with('success', 'Initial payment updated successfully. Balance and installments recalculated.');
        } catch (\Exception $e) {
            // Revert to previous values in case of error
            $billing->initial_payment = $previousInitialPayment;
            $billing->balance_due = $previousBalanceDue;
            // Note: You might want to restore previous installment values too if needed

            return back()->with('error', 'An error occurred while updating the payment. Changes were not saved.');
        }
    }


    public function show($id)
    {
        $admission = Admission::with('courseMapping.program')->findOrFail($id);
        return view('registrar.enrollment.show', compact('admission'));
    }

    public function printCor($studentId)
    {
        // Get the latest enrollment record for the student
        $enrollment = Enrollment::with('courseMapping.program', 'billing')
            ->where('student_id', $studentId)
            ->latest('enrollment_date') // assumes latest enrollment is the one you want
            ->firstOrFail();

        $courseMappingId = $enrollment->course_mapping_id;

        // Get miscellaneous fees tied to this course mapping
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

        $billing = $enrollment->currentBilling();


        return view('registrar.enrollment.print-cor', compact('enrollment', 'formattedCourses', 'miscFees', 'billing'));
    }




    public function index()
    {
        // Fetch admissions data
        $admissions = $this->getAdmissionsForActiveSchoolYear();

        // Fetch course mappings
        $courseMappings = $this->getUniqueSortedCourseMappings();

        // Fetch all courses
        $allCourses = $this->getAllCourses();

        // Fetch scholarships
        $scholarships = $this->getActiveScholarships();

        // Calculate total units if a mapping is selected
        $selectedMappingId = request('selected_mapping_id');
        $totalUnits = $this->calculateTotalUnits($selectedMappingId);

        // Fetch regions
        $regions = $this->getRegions();

        // Pass all data to view
        return view('registrar.enrollment.enrollment', compact(
            'admissions',
            'courseMappings',
            'allCourses',
            'scholarships',
            'selectedMappingId',
            'regions',
            'totalUnits'
        ));
    }

    private function getAdmissionsForActiveSchoolYear()
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['error' => 'No active school year found'], 404);
        }

        return Admission::with([
            'courseMapping.program',
            'courseMapping.yearLevel',
            'courseMapping.semester',
            'billing'
        ])->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get();
    }

    private function getUniqueSortedCourseMappings()
    {
        return ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');
    }

    private function getAllCourses()
    {
        return \App\Models\Course::orderBy('id')->get();
    }

    private function getActiveScholarships()
    {
        return Scholarship::where('status', 1)->orderBy('name')->get();
    }

    private function calculateTotalUnits($selectedMappingId)
    {
        if (!$selectedMappingId) {
            return 0;
        }

        $selectedMapping = ProgramCourseMapping::find($selectedMappingId);

        if (!$selectedMapping) {
            return 0;
        }

        $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
            ->where('year_level_id', $selectedMapping->year_level_id)
            ->where('semester_id', $selectedMapping->semester_id)
            ->where('effective_sy', $selectedMapping->effective_sy)
            ->get();

        $courseIds = $matchingMappings->pluck('course_id')->unique();

        return \App\Models\Course::whereIn('id', $courseIds)->sum('units');
    }

    private function getRegions()
    {
        return RefRegion::with('provinces.cities.barangays')->get();
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
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'school_year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'course_mapping_id' => 'nullable|exists:program_course_mappings,id',
            'scholarship' => 'nullable|string',
        ]);

        $existingStudent = Admission::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('middle_name', $request->middle_name)
            ->where('birthdate', $request->birthdate)
            ->first();

        if ($existingStudent) {
            return redirect()->back()->with('error', 'This student is already an existing student!');
        }

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $yearPrefix = date('y');

        do {
            $latest = Admission::where('student_id', 'like', "$yearPrefix-%")
                ->orderBy('student_id', 'desc')
                ->first();

            if ($latest) {
                $lastNumber = (int) substr($latest->student_id, -3);
                $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '001';
            }

            $studentId = "$yearPrefix-$nextNumber";
        } while (Admission::where('student_id', $studentId)->exists());

        // Determine admission status based on scholarship
        $status = 'Pending';
        if ($request->scholarship !== 'none' && $request->scholarship !== null) {
            $scholarship = Scholarship::find($request->scholarship);
            if ($scholarship && stripos($scholarship->name, 'Top Notcher') !== false) {
                $status = 'Enrolled';
            }
        }

        $admission = Admission::create([
            'student_id' => $studentId,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'region' => $request->region,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
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
            'lrn' => $request->lrn,
            'school_year' => $activeSchoolYear ? $activeSchoolYear->name : $request->school_year,
            'semester' => $activeSchoolYear ? $activeSchoolYear->semester : $request->semester,
            'status' => $status,
        ]);

        Enrollment::create([
            'student_id' => $admission->student_id,
            'school_year' => $admission->school_year,
            'semester' => $admission->semester,
            'course_mapping_id' => $request->course_mapping_id,
            'status' => 'Pending',
            'enrollment_type' => 'new',
            'enrollment_date' => now(),
            'scholarship_id' => ($request->scholarship && $request->scholarship !== 'none') ? $request->scholarship : null,
        ]);

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

        $tuitionFee = $request->input('tuition_fee');
        $discountValue = 0;
        $tuitionFeeDiscount = $tuitionFee;
        $miscFee = 0;
        $balanceDue = null;

        if ($request->scholarship !== 'none' && $request->scholarship !== null) {
            $scholarship = Scholarship::find($request->scholarship);

            if ($scholarship) {
                if (stripos($scholarship->name, 'Top Notcher') !== false) {
                    $discountValue = $tuitionFee;
                    $tuitionFeeDiscount = 0;
                    $miscFee = 0;
                    $balanceDue = 0;
                } elseif ($scholarship->discount) {
                    $discountValue = $tuitionFee * ($scholarship->discount / 100);
                    $tuitionFeeDiscount = $tuitionFee - $discountValue;
                }
            }
        }

        if ($balanceDue === null && $request->course_mapping_id) {
            $miscFee = MiscFee::where('program_course_mapping_id', $request->course_mapping_id)->sum('amount');
        }

        $initialPayment = 0;
        $oldAccounts = 0;

        if ($balanceDue === null) {
            $totalAssessment = $tuitionFeeDiscount + $miscFee + $oldAccounts;
            $balanceDue = $totalAssessment - $initialPayment;
        }

        Billing::create([
            'student_id' => $admission->student_id,
            'school_year' => $admission->school_year,
            'semester' => $admission->semester,
            'tuition_fee' => $tuitionFee,
            'discount' => $discountValue,
            'tuition_fee_discount' => $tuitionFeeDiscount,
            'misc_fee' => $miscFee,
            'old_accounts' => $oldAccounts,
            'total_assessment' => $totalAssessment ?? 0,
            'initial_payment' => $initialPayment,
            'balance_due' => $balanceDue,
            'is_full_payment' => false,
        ]);

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

        return redirect()->route('admissions.index')->with('success', 'Admission, enrollment, and billing created successfully!');
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
        $courses = []; // Array to store course info

        if ($mappingId) {
            $selectedMapping = ProgramCourseMapping::find($mappingId);

            if ($selectedMapping) {
                $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
                    ->where('year_level_id', $selectedMapping->year_level_id)
                    ->where('semester_id', $selectedMapping->semester_id)
                    ->where('effective_sy', $selectedMapping->effective_sy)
                    ->get();

                $courseIds = $matchingMappings->pluck('course_id')->unique();

                // Get all courses with their names and units
                $courses = \App\Models\Course::whereIn('id', $courseIds)
                    ->select('id', 'name', 'units')
                    ->get()
                    ->toArray();

                $totalUnits = array_sum(array_column($courses, 'units'));
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
            'courses' => $courses, // Include course data in response
        ]);
    }


    public function create()
    {
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel'])
            ->get()
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        $allCourses = \App\Models\Course::orderBy('code')->get();

        $scholarships = Scholarship::where('status', 1)->orderBy('name')->get(); // Only active ones

        $regions = RefRegion::with('provinces.cities.barangays')->get();

        return view('registrar.enrollment.enrollment', compact('courseMappings', 'allCourses', 'scholarships', 'regions'));
    }
}
