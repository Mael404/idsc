<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Admission;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function voidOtherPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::find($request->payment_id);

        if ($payment->is_void) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is already voided.',
            ]);
        }

        $payment->is_void = true;
        $payment->voided_at = Carbon::now();
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment voided successfully.',
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number' => 'required|string|unique:payments,or_number',
            'grading_period' => 'required|in:prelims,midterms,prefinals,finals',
            'remarks' => 'nullable|string',
        ]);

        $gradingPeriod = $request->grading_period;
        $studentId = $request->student_id;
        $paymentAmount = $request->payment_amount;
        $orNumber = $request->or_number;
        $remarks = $request->remarks;

        // Fetch the active school year
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return redirect()->back()->withErrors(['error' => 'No active school year found.']);
        }

        // Fetch the billing record for the student
        $billing = Billing::where('student_id', $studentId)
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

        // Deduct payment from prelims_due, midterms_due, prefinals_due, and finals_due
        $dues = ['prelims_due', 'midterms_due', 'prefinals_due', 'finals_due'];

        $remainingPayment = $paymentAmount;  // use this to deduct from dues

        foreach ($dues as $due) {
            if ($remainingPayment <= 0) {
                break;
            }

            if ($billing->$due > 0) {
                if ($remainingPayment >= $billing->$due) {
                    $remainingPayment -= $billing->$due;
                    $billing->$due = 0;
                } else {
                    $billing->$due -= $remainingPayment;
                    $remainingPayment = 0;
                }
            }
        }

        // Update balance_due to the sum of the updated dues
        $billing->balance_due = $billing->prelims_due
            + $billing->midterms_due
            + $billing->prefinals_due
            + $billing->finals_due;

        $billing->save();

        // Update status in the Admission table
        $admission = Admission::where('student_id', $studentId)->first();

        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        } else {
            return redirect()->back()->withErrors(['error' => 'Admission record not found for this student.']);
        }

        // Log the payment with remaining_balance
        Payment::create([
            'student_id'        => $studentId,
            'school_year'       => $activeSchoolYear->name,
            'semester'          => $activeSchoolYear->semester,
            'grading_period'    => $gradingPeriod,
            'amount'            => $paymentAmount,
            'or_number'         => $orNumber,
            'remarks'           => $remarks,
            'payment_date'      => now(),
            'remaining_balance' => $billing->balance_due,  // store remaining balance here
        ]);

        return redirect()->back()->with('success', 'Payment processed successfully!');
    }
    public function input(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number' => 'required|string|unique:payments,or_number',
            'remarks' => 'nullable|string',
            'payment_type' => 'nullable|string', // Optional since it defaults
        ]);

        $studentId = $request->student_id;
        $paymentAmount = $request->payment_amount;
        $orNumber = $request->or_number;
        $remarks = $request->remarks;
        $paymentType = $request->payment_type ?? 'others';

        // Fetch the active school year
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return redirect()->back()->withErrors(['error' => 'No active school year found.']);
        }

        // Log the payment (no billing update logic here)
        Payment::create([
            'student_id'   => $studentId,
            'school_year'  => $activeSchoolYear->name,
            'semester'     => $activeSchoolYear->semester,
            'amount'       => $paymentAmount,
            'or_number'    => $orNumber,
            'remarks'      => $remarks,
            'payment_type' => $paymentType,
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function voidPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        try {
            $payment = Payment::findOrFail($request->payment_id);

            // Check if it's already pending or voided
            if ($payment->status === 'pending_void') {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment is already pending for void approval.'
                ]);
            }

            if ($payment->is_void) {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment has already been voided.'
                ]);
            }

            // Update status to pending
            $payment->status = 'pending_void';
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Void request submitted. Awaiting approval from accounting.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting void request: ' . $e->getMessage()
            ]);
        }
    }
}
