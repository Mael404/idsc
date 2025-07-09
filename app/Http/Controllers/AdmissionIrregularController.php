<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdmissionIrregularController extends Controller
{
   public function storeIrregular(Request $request)
{
    Log::debug('Irregular admission submission:', $request->all());
    
    // Validate the request data
    $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'contact_number' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'tuition_fee' => 'required|numeric',
    ]);

    // Check for existing student
    $existingStudent = Admission::where('first_name', $request->first_name)
        ->where('last_name', $request->last_name)
        ->where('middle_name', $request->middle_name)
        ->where('birthdate', $request->birthdate)
        ->first();

    if ($existingStudent) {
        return redirect()->back()->with('error', 'This student is already an existing student!');
    }

    // Generate student ID
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

    // Get active school year
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

    // Determine admission status based on scholarship
    $status = 'Pending';
    if ($request->scholarship !== 'none' && $request->scholarship !== null) {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship && stripos($scholarship->name, 'Top Notcher') !== false) {
            $status = 'Enrolled';
        }
    }

    // Create the admission record
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
        'major' => $request->major,
        'admission_status' => 'irregular',
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

    // Create enrollment record
    Enrollment::create([
        'student_id' => $admission->student_id,
        'school_year' => $admission->school_year,
        'semester' => $admission->semester,
        'status' => 'Pending',
        'enrollment_type' => 'irregular',
        'enrollment_date' => now(),
        'scholarship_id' => ($request->scholarship && $request->scholarship !== 'none') ? $request->scholarship : null,
    ]);

    // Billing Calculation - Fixed Version
    $tuitionFee = (float) $request->input('tuition_fee', 0);
    $discountValue = 0;
    $tuitionFeeDiscount = $tuitionFee;
    $miscFee = 500; // Set default misc fee or get from request if available
    $oldAccounts = 0;
    $initialPayment = 0;

    // Handle scholarship discounts
    if ($request->scholarship !== 'none' && $request->scholarship !== null) {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship) {
            if (stripos($scholarship->name, 'Top Notcher') !== false) {
                $discountValue = $tuitionFee;
                $tuitionFeeDiscount = 0;
                $miscFee = 0;
            } elseif ($scholarship->discount) {
                $discountValue = $tuitionFee * ($scholarship->discount / 100);
                $tuitionFeeDiscount = $tuitionFee - $discountValue;
            }
        }
    }

    // Calculate totals
    $totalAssessment = $tuitionFeeDiscount + $miscFee + $oldAccounts;
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
    ]);

    // Assign selected courses to the student
    if ($request->has('courses')) {
        foreach ($request->courses as $courseData) {
            StudentCourse::create([
                'student_id' => $admission->student_id,
                'course_id' => $courseData['course_id'],
                'school_year' => $admission->school_year,
                'semester' => $admission->semester,
                'status' => 'Pending',
                'override_prereq' => $courseData['override_prereq'] ?? 0,
            ]);
        }
    }

    return redirect()->route('admissions.index')->with('success', 'Irregular student admission created successfully!');
}
    public function calculateIrregularTuition(Request $request)
    {
        $courseIds = $request->input('course_ids', []); // Expects an array of course IDs
        $totalUnits = 0;
        $unitPrice = 0;
        $tuitionFee = 0;

        if (!empty($courseIds)) {
            $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        if ($activeSchoolYear) {
            $unitPrice = $activeSchoolYear->default_unit_price ?? 0;
            $tuitionFee = $totalUnits * $unitPrice;
        }

        return response()->json([
            'total_units' => $totalUnits,
            'tuition_fee' => $tuitionFee,
            'unit_price' => $unitPrice
        ]);
    }
}
