<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\ShsBilling;
use App\Models\ShsEnrollment;
use App\Models\ShsPayment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShsPaymentController extends Controller
{
    //
    public function store(Request $request)
    {

        try {
            $request->validate([
                'lrn_number' => 'required|exists:shs_billings,student_lrn',
                'payment_amount' => 'required|numeric|min:0.01',
                'or_number' => 'required_if:payment_method,CASH|nullable|string|unique:shs_payments,or_number',
                'payment_method' => 'required|string',
                'ref_number' => 'nullable|string',
            ]);
            $lrn_number = $request->lrn_number;
            $paymentAmount = $request->payment_amount;
            $orNumber = $request->or_number;
            $paymentMethod = $request->payment_method;
            $refNumber = $request->ref_number;

            // Fetch the active school year
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return redirect()->back()->withErrors(['error' => 'No active school year found.']);
            }
            // Fetch the billing record for the student
            $billing = ShsBilling::where('student_lrn', $lrn_number)
                ->where('school_year', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->first();

            if (!$billing) {
                return redirect()->back()->withErrors(['error' => 'Billing record not found for this student.']);
            }

            // Check if payment amount is greater than the current balance due
            if ($paymentAmount > $billing->balance_due) {
                return redirect()->back()->with('error', 'The payment amount cannot be greater than the current balance due.');
            }
            $student = Student::where('lrn_number', $lrn_number)->first();
            $enrollment = ShsEnrollment::where('student_id', $student->student_id)->first();
            if ($paymentMethod === 'CASH') {
                $billing->balance_due = $paymentAmount < $billing->balance_due ? $billing->balance_due - $paymentAmount : 0;
                $billing->save();
            }

            // Update status in the Admission table
            if ($enrollment) {
                $enrollment->status = 'Enrolled';
                $enrollment->save();
            } else {
                return redirect()->back()->withErrors(['error' => 'Enrollment record not found for this student.']);
            }
            $enrollment_id = $enrollment->enrollment_id;
            if (empty($paymentAmount)) {
                dd('paymentAmount is empty', $paymentAmount);
            }
            $status = ($paymentMethod === 'CASH' ?  'completed' : 'pending');
            $payment = ShsPayment::create([
                'enrollment_id'     => $enrollment_id,
                'school_year'       => $activeSchoolYear->name,
                'amount'            => $paymentAmount,
                'or_number'         => $orNumber,
                'payment_date'      => now(),
                'balance_due'       => $billing->balance_due,
                'processed_by'      => Auth::id(),
                'payment_method'    => $paymentMethod,
                'ref_number'        => $refNumber,
                'status'            => $status,
            ]);
            // $payment->status = $status;
            // $payment->save();
            return redirect()->back()->with('success', 'Payment processed successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function markAsCompleted(Request $request)
    {


        try {
            
            $request->validate([
                'payment_id' => 'required|exists:shs_payments,payment_id',
                'or_number' => 'required|nullable|string|unique:shs_payments,or_number',
                'remarks' => 'nullable|string',
            ]);
            
            $payment = ShsPayment::where('payment_id', $request->payment_id)->first();
            $payment->status = 'completed';
            $payment->or_number = $request->or_number;

            // $payment->remarks = $request->remarks;
            $payment->save();
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            $enrollment = ShsEnrollment::where('enrollment_id', $payment->enrollment_id)->first();
            $student = $enrollment->student;
            // Fetch the billing record for the student
            $billing = ShsBilling::where('student_lrn', $student->lrn_number)
                ->where('school_year', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->first();
            $paymentAmount = $payment->amount;
            $billing->balance_due = $paymentAmount < $billing->balance_due ? $billing->balance_due - $paymentAmount : 0;
            $billing->save();

            // Update balance_due to the sum of the updated dues
            $billing->balance_due = $billing->prelims_due
                + $billing->midterms_due
                + $billing->prefinals_due
                + $billing->finals_due;

            $billing->save();

            return redirect()->back()->with('success', 'Payment marked as completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error marking payment as completed: ' . $e->getMessage());
        }
    }
}
