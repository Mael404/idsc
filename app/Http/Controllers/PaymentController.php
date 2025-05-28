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
            'remarks' => 'nullable|string',
        ]);

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
            'student_id'   => $studentId,
            'school_year'  => $activeSchoolYear->name,
            'semester'     => $activeSchoolYear->semester,
            'amount'       => $request->payment_amount,
            'or_number'    => $orNumber,
            'remarks'      => $remarks,
            'payment_date' => now(),
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
            'student_id' => 'required',
            'amount' => 'required|numeric',
            'semester' => 'required',
            'school_year' => 'required'
        ]);

        DB::beginTransaction();

        try {
            // Find the billing record with matching student_id, semester, and school_year
            $billing = Billing::where('student_id', $request->student_id)
                ->where('semester', $request->semester)
                ->where('school_year', $request->school_year)
                ->first();

            if (!$billing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Billing record not found for this student, semester, and school year'
                ]);
            }

            // Add the amount back to balance_due
            $billing->balance_due += $request->amount;
            $billing->save();

            // Mark the payment as voided (you might want to add a 'status' or 'is_void' column to your payments table)
            $payment = Payment::find($request->payment_id);
            $payment->is_void = true; // Assuming you have this column
            $payment->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment voided successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error voiding payment: ' . $e->getMessage()
            ]);
        }
    }
}
