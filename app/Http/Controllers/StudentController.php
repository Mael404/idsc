<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\ShsBilling;
use App\Models\ShsEnrollment;
use App\Models\Strand;
use App\Models\Student;
use App\Models\TypeOfPee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exceptions;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class StudentController extends Controller
{
    //
    public function checkBalance(Request $request)
    {
        try {
            $id = $request->student_id;

            $connector = new WindowsPrintConnector("POS58D_UB");
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("BALANCE RECEIPT\n");
            $printer->feed();
            $printer->cut();
            $printer->close();

            return response()->json(['message' => 'Receipt printed successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to print receipt: ' . $e->getMessage()], 500);
        }
    }
    public function promoteStudent(Request $request, $id)
    {
        try {
            $student = Student::find($id);
            $enrollment = $student->enrollment;

            $enrollment->grade_level = 12;
            $enrollment->save();

            return redirect()->back()->with('success', 'Student has been successfully promoted to Grade 12.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.')->setStatusCode(500);
        }
    }
    public function updateInitialPayment(Request $request, $id)
    {
        $billing = ShsBilling::findOrFail($id);

        // Allow both fields to be nusllable
        $request->validate([
            'initial_payment' => 'nullable|numeric|min:0',
            'manual_old_balance' => 'nullable|numeric|min:0',
        ]);

        $newInitialPayment = $request->input('initial_payment', null);
        $manualOldBalance = $request->input('manual_old_balance', null);

        // 🚨 Prevent submission if both fields are empty
        if (is_null($newInitialPayment) && is_null($manualOldBalance)) {
            return back()->with('error', 'Please fill in at least one field (Initial Payment or Manual Old Balance).');
        }

        try {
            // Save old values in case of rollback
            $previousInitialPayment = $billing->initial_payment;
            $previousBalanceDue = $billing->balance_due;
            $previousOldAccounts = $billing->old_accounts;

            // ✅ Handle manual old balance
            if (!is_null($manualOldBalance)) {
                // Adjust old_accounts and balance_due
                $billing->balance_due += ($manualOldBalance - $billing->old_accounts);
                $billing->old_accounts = $manualOldBalance;
            }

            // ✅ Handle initial payment
            if (!is_null($newInitialPayment)) {
                if ($newInitialPayment > $billing->total_assessment) {
                    return back()->with('error', 'Initial payment cannot be greater than the total assessment.');
                }

                $initialPaymentDifference = $newInitialPayment - $billing->initial_payment;

                if (($billing->balance_due - $initialPaymentDifference) < 0) {
                    return back()->with('error', 'The payments cannot result in a negative balance due.');
                }

                $billing->initial_payment = $newInitialPayment;
                $billing->balance_due -= $initialPaymentDifference;
            }

            // ✅ Recalculate installment schedule
            if (!$billing->balance_due > 0) {
                // $installment = $billing->balance_due / 4;
                // $billing->prelims_due = $installment;
                // $billing->midterms_due = $installment;
                // $billing->prefinals_due = $installment;
                // $billing->finals_due = $installment;
                $billing->balance_due = 0; // ensure clean zero
            }

            $billing->save();

            return back()->with('success', 'Payment updated successfully. Manual old balance applied if provided.');
        } catch (\Exception $e) {
            // Rollback
            $billing->initial_payment = $previousInitialPayment;
            $billing->balance_due = $previousBalanceDue;
            $billing->old_accounts = $previousOldAccounts;

            return back()->with('error', 'An error occurred while updating. Changes were not saved.');
        }
    }
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'track' => 'required|in:1,2',
                'strandID' => 'required|in:1,2,3,4,5',
                'section' => 'required|string|max:100',
                'lastName' => 'required|string|max:100',
                'firstName' => 'required|string|max:100',
                'middleName' => 'nullable|string|max:100',
                'gender' => 'required|in:Male,Female',
                'civilStatus' => 'nullable|string|max:50',
                'birthDate' => 'required|date',
                'birthPlace' => 'nullable|string|max:150',
                'address' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:15',
                'emailAddress' => 'required|email|max:150',
                'secondarySchoolName' => 'nullable|string|max:255',
                'secondarySchoolAddress' => 'nullable|string|max:255',
                'lrn' => 'required|string|max:20',
                'escNumber' => 'nullable|string|max:20',
                'secondarySchoolClassification' => 'required|in:Private,Public',
                'type_of_pee' => 'required|exists:types_of_pee,id',
                'fathersName' => 'required|string|max:100',
                'fathersAddress' => 'required|string|max:255',
                'fathersOccupation' => 'nullable|string|max:100',
                'fathersContactNumber' => 'nullable|string|max:15',

                'mothersName' => 'required|string|max:100',
                'motherAddress' => 'required|string|max:255',
                'mothersOccupation' => 'nullable|string|max:100',
                'mothersContactNumber' => 'nullable|string|max:15',

                'guardianName' => 'required|string|max:100',
                'guardianAddress' => 'required|string|max:255',
                'relationship' => 'required|string|max:50',
                'guardianContactNo' => 'required|string|max:15',

                'governmentAssistance' => 'nullable|string|max:100',
                // 'status' => 'required|in:Enrolled,Dropped,Transferee,Graduated',
                'grade' => 'required|string|max:10',
                // 'semester' => 'required|exists:semesters,id',
                'schoolYearID' => 'nullable|string|max:20',
                'region' => 'required|string|max:50',
                'divisionID' => 'nullable|string|max:50',
                'summer' => 'nullable|string|max:50',
                'voucherRecipient' => 'nullable',
                'yearGraduatedID' => 'nullable|string|max:50',
                'awardID' => 'nullable|string|max:50',
                'clearance' => 'nullable|string|max:50',
            ]);

            $track = $request->track;
            $strandID = $request->strandID;
            $section = $request->section;
            $lastName = $request->lastName;
            $firstName = $request->firstName;
            $middleName = $request->middleName;
            $gender = $request->gender;
            $civilStatus = $request->civilStatus;
            $birthdate = $request->birthDate;
            $birthPlace = $request->birthPlace;
            $address = $request->address;
            $mobileNumber = $request->mobileNumber;
            $emailAddress = $request->emailAddress;
            $secondarySchoolName = $request->secondarySchoolName;
            $secondarySchoolAddress = $request->secondarySchoolAddress;
            $lrn = $request->lrn;
            $escNumber = $request->escNumber;
            $secondarySchoolClassification = $request->secondarySchoolClassification;

            $fathersName = $request->fathersName;
            $fathersAddress = $request->fathersAddress;
            $fathersOccupation = $request->fathersOccupation;
            $fathersContactNumber = $request->fathersContactNumber;

            $mothersName = $request->mothersName;
            $motherAddress = $request->motherAddress;
            $mothersOccupation = $request->mothersOccupation;
            $mothersContactNumber = $request->mothersContactNumber;

            $guardianName = $request->guardianName;
            $guardianAddress = $request->guardianAddress;
            $relationship = $request->relationship;
            $guardianContactNo = $request->guardianContactNo;

            $governmentAssistance = $request->governmentAssistance;
            $status = 'Pending';
            $grade = $request->grade;

            $schoolYearID = $request->schoolYearID;
            $region = $request->region;
            $divisionID = $request->divisionID;
            $summer = $request->summer;
            $voucherRecipient = $request->voucherRecipient === "on" ?  true : false;
            $yearGraduatedID = $request->yearGraduatedID;
            $awardID = $request->awardID;
            $clearance = $request->clearance;
            $peeId = $request->type_of_pee;
            $existingStudent = Student::where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->where('middle_name', $middleName)
                ->where('birthday', $birthdate)
                ->first();
            if ($existingStudent) {
                return redirect()->back()->with('error', 'This student is already an existing student!');
            }
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            $isFullyPaid = false;
            $semesterName = $activeSchoolYear->semester;
            $activeSem = Semester::where('name', $semesterName)->first();
            $student = Student::create([
                'lrn_number' => $lrn,
                'last_name' => $lastName,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'birthday' => $birthdate,
                'gender' => $gender,
                'civil_status' => $civilStatus,
                'year_graduated' => $yearGraduatedID,
                'checklist_clearance' => $clearance,
                'birthplace' => $birthPlace,
                'address' => $address,
                'mobile_number' => $mobileNumber,
                'email_address' => $emailAddress,
                'secondary_school_name' => $secondarySchoolName,
                'secondary_address' => $secondarySchoolAddress,
                'region' => $region,
                'division_id' => $divisionID,
                'last_school_attended' => $secondarySchoolName,
                'classification' => $secondarySchoolClassification,
                'father_name' => $fathersName,
                'father_address' => $fathersAddress,
                'father_occupation' => $fathersOccupation,
                'father_contact' => $fathersContactNumber,
                'mother_name' => $mothersName,
                'mother_address' => $motherAddress,
                'mother_occupation' => $mothersOccupation,
                'mother_contact' => $mothersContactNumber,
                'guardian_name' => $guardianName,
                'guardian_address' => $guardianAddress,
                'guardian_relationship' => $relationship,
                'guardian_contact' => $guardianContactNo,
            ]);
            $strand = Strand::where('strand_id', $strandID)->first();

            $typeOfpee = TypeOfPee::find($peeId);

            ShsEnrollment::create([
                'student_id' => $student->student_id,
                'school_years' => $activeSchoolYear->name,
                'semester' => $semesterName,
                'grade_level' => $grade,
                'strand' => $strand->strand_name,
                'track' => $track,
                'section' => $section,
                'status' => $status,
                'classification' => $secondarySchoolClassification,
                'esc_number' => $escNumber,
                'voucher_recipient_checklist' => $voucherRecipient,
                'type_of_payee' => $typeOfpee->name,
                'school_year_id' => $activeSchoolYear->id,
                'semester_id' => $activeSem->id,
                'school_year' => $activeSchoolYear->name,
                'strand_id'    => $strand->strand_id
            ]);

            ShsBilling::create([
                'student_lrn' => $lrn,
                'school_year' => $activeSchoolYear->name,
                'semester' => $semesterName,
                'tuition_fee' => $typeOfpee->tuition,
                'discount' => 0.00,
                'tuition_fee_discount' => 0.00,
                'misc_fee' => 0.00,
                'old_accounts' => 0.00,
                'total_assessment' => $typeOfpee->tuition,
                'initial_payment' => 0.00,
                'balance_due' => $typeOfpee->tuition,
                'is_full_payment' => false,
                'prelims_due' => 0.00,
                'midterms_due' => 0.00,
                'prefinals_due' => 0.00,
                'finals_due' => 0.00,
            ]);
            return redirect()->route('registrar.enrollment.new.shs')->with('success', 'Student, enrollment, and billing created successfully!');
        } catch (\Exception $e) {
            echo $e;
            // return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
