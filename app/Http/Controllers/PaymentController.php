<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Admission; // Include the Admission model

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number' => 'required|string|unique:payments,or_number', // Validate OR number
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

        // Deduct payment from balance
        $billing->balance_due -= $paymentAmount;

        // Ensure balance is not negative
        if ($billing->balance_due < 0) {
            $billing->balance_due = 0;
        }

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
            'amount' => $paymentAmount,
            'or_number' => $orNumber, // Store the OR number
            'remarks' => $remarks,
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment processed successfully!');
    }
}
