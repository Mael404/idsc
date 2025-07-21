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
    $validated = $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'birthdate' => 'required|date',
        'contact_number' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'tuition_fee' => 'required|numeric',
        'misc_fees' => 'sometimes|array',
        // Include all other fields you need
    ]);

    // Check for existing student
    $existingStudent = Admission::where('first_name', $request->first_name)
        ->where('last_name', $request->last_name)
        ->where('middle_name', $request->middle_name)
        ->where('birthdate', $request->birthdate)
        ->first();

    if ($existingStudent) {
        return redirect()->back()->with('error', 'Student already exists!');
    }

    // Generate student ID
    $yearPrefix = date('y');
    do {
        $latest = Admission::where('student_id', 'like', "$yearPrefix-%")
            ->orderBy('student_id', 'desc')
            ->first();

        $nextNumber = $latest ? 
            str_pad((int) substr($latest->student_id, -3) + 1, 3, '0', STR_PAD_LEFT) : 
            '001';

        $studentId = "$yearPrefix-$nextNumber";
    } while (Admission::where('student_id', $studentId)->exists());

    // Get active school year
    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

    // Determine admission status
    $status = 'Pending';
    if ($request->filled('scholarship') && $request->scholarship !== 'none') {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship && stripos($scholarship->name, 'Top Notcher') !== false) {
            $status = 'Enrolled';
        }
    }

    // Create admission record (all original fields preserved)
    $admissionData = $request->only([
        'last_name', 'first_name', 'middle_name', 'address_line1', 'address_line2',
        'region', 'province', 'city', 'barangay', 'zip_code', 'contact_number',
        'email', 'father_last_name', 'father_first_name', 'father_middle_name',
        'father_contact', 'father_profession', 'father_industry', 'mother_last_name',
        'mother_first_name', 'mother_middle_name', 'mother_contact', 'mother_profession',
        'mother_industry', 'gender', 'birthdate', 'birthplace', 'citizenship',
        'religion', 'civil_status', 'course_mapping_id', 'major', 'student_no',
        'admission_year', 'previous_school', 'previous_school_address',
        'elementary_school', 'elementary_address', 'secondary_school', 'secondary_address',
        'honors', 'lrn'
    ]);

    $admission = Admission::create(array_merge($admissionData, [
        'student_id' => $studentId,
        'admission_status' => 'irregular',
        'scholarship_id' => $request->scholarship !== 'none' ? $request->scholarship : null,
        'school_year' => $activeSchoolYear?->name ?? $request->school_year,
        'semester' => $activeSchoolYear?->semester ?? $request->semester,
        'status' => $status
    ]));

    // Create enrollment
    Enrollment::create([
        'student_id' => $admission->student_id,
        'school_year' => $admission->school_year,
        'semester' => $admission->semester,
        'course_mapping_id' => $request->course_mapping_id,
        'status' => 'Pending',
        'enrollment_type' => 'irregular',
        'enrollment_date' => now(),
        'scholarship_id' => $request->scholarship !== 'none' ? $request->scholarship : null,
    ]);

    // Process Misc Fees
    $miscFeeTotal = 0;
    $miscFeesData = [];
    
    if ($request->has('misc_fees')) {
        foreach ($request->misc_fees as $id => $fee) {
            $amount = (float) ($fee['amount'] ?? 0);
            $miscFeeTotal += $amount;
            
            $miscFeesData[] = [
                'student_id' => $studentId,
                'school_year' => $admission->school_year,
                'semester' => $admission->semester,
                'fee_name' => $fee['name'] ?? 'Custom Fee',
                'amount' => $amount,
                'is_required' => (bool) ($fee['is_required'] ?? false),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    }

    // Handle billing with scholarships
    $tuitionFee = (float) $request->tuition_fee;
    $discountValue = 0;
    $tuitionFeeDiscount = $tuitionFee;

    if ($request->filled('scholarship') && $request->scholarship !== 'none') {
        $scholarship = Scholarship::find($request->scholarship);
        if ($scholarship) {
            if (stripos($scholarship->name, 'Top Notcher') !== false) {
                $discountValue = $tuitionFee;
                $tuitionFeeDiscount = 0;
                $miscFeeTotal = 0; // Waive all fees
            } elseif ($scholarship->discount) {
                $discountValue = $tuitionFee * ($scholarship->discount / 100);
                $tuitionFeeDiscount = $tuitionFee - $discountValue;
            }
        }
    }

    // Create billing
    $billing = Billing::create([
        'student_id' => $studentId,
        'school_year' => $admission->school_year,
        'semester' => $admission->semester,
        'tuition_fee' => $tuitionFee,
        'discount' => $discountValue,
        'tuition_fee_discount' => $tuitionFeeDiscount,
        'misc_fee' => $miscFeeTotal,
        'old_accounts' => 0, // Default
        'total_assessment' => $tuitionFeeDiscount + $miscFeeTotal,
        'initial_payment' => 0, // Default
        'balance_due' => $tuitionFeeDiscount + $miscFeeTotal,
        'is_full_payment' => false,
    ]);

    // Save misc fees (bulk insert for performance)
    if (!empty($miscFeesData)) {
        \App\Models\StudentMiscFee::insert(
            array_map(function($fee) use ($billing) {
                return array_merge($fee, ['billing_id' => $billing->id]);
            }, $miscFeesData)
        );
    }

    // Assign courses
    if ($request->has('courses')) {
        $coursesData = [];
        foreach ($request->courses as $courseData) {
            $coursesData[] = [
                'student_id' => $studentId,
                'course_id' => $courseData['course_id'],
                'school_year' => $admission->school_year,
                'semester' => $admission->semester,
                'status' => 'Pending',
                'override_prereq' => $courseData['override_prereq'] ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        StudentCourse::insert($coursesData);
    }

    return redirect()->route('admissions.index')
        ->with('success', 'Irregular student created successfully!');
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
