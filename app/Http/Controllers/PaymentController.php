<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Admission;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number' => 'required|string|unique:payments,or_number',
            'remarks' => 'nullable|string',
        ]);

        $studentId = $request->student_id;
        $paymentAmount = $request->payment_amount;
        $orNumber = $request->or_number;
        $remarks = $request->remarks;

        // Fetch the billing record for the student
        $billing = Billing::where('student_id', $studentId)->first();

        if (!$billing) {
            return redirect()->back()->withErrors(['error' => 'Billing record not found for this student.']);
        }

        // Deduct payment from prelims_due, midterms_due, prefinals_due, and finals_due
        $dues = ['prelims_due', 'midterms_due', 'prefinals_due', 'finals_due'];

        foreach ($dues as $due) {
            if ($paymentAmount <= 0) {
                break;
            }

            if ($billing->$due > 0) {
                if ($paymentAmount >= $billing->$due) {
                    $paymentAmount -= $billing->$due;
                    $billing->$due = 0;
                } else {
                    $billing->$due -= $paymentAmount;
                    $paymentAmount = 0;
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

        // Log the payment
        Payment::create([
            'student_id' => $studentId,
            'amount' => $request->payment_amount,
            'or_number' => $orNumber,
            'remarks' => $remarks,
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment processed successfully!');
    }
}
