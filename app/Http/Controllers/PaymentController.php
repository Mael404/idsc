<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\OtherPayment;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function voidOtherPayment(Request $request)
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


    
    public function markAsCompleted(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'or_number' => 'required|string|unique:payments,or_number,',
            'remarks' => 'nullable|string',
        ]);

        try {
            $payment = Payment::findOrFail($request->payment_id);
            $payment->status = 'completed';
            $payment->or_number = $request->or_number;
            $payment->remarks = $request->remarks;
            $payment->save();
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            $studentId = $payment->student_id;

            if ($payment->enrollment_id) {
                $enrollment = Enrollment::where('id', $payment->enrollment_id)
                    ->where('school_year', $activeSchoolYear->name)
                    ->where('semester', $activeSchoolYear->semester)
                    ->first();
                $enrollment->status = 'Enrolled';
                $enrollment->save();
            }

            // Fetch the billing record for the student
            $billing = Billing::where('student_id', $studentId)
                ->where('school_year', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->first();

            // Deduct payment from prelims_due, midterms_due, prefinals_due, and finals_due
            $dues = ['prelims_due', 'midterms_due', 'prefinals_due', 'finals_due'];

            if ($payment->grading_period !== 'Initial Payment') {
                $paymentAmount = $payment->amount;
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
            }


            // Update status in the Admission table
            $admission = Admission::where('student_id', $studentId)->first();

            if ($admission) {
                $admission->status = 'Enrolled';
                $admission->save();
            } else {
                return redirect()->back()->withErrors(['error' => 'Admission record not found for this student.']);
            }

            return redirect()->back()->with('success', 'Payment marked as completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error marking payment as completed: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number' => 'required_if:payment_method,CASH|nullable|string|unique:payments,or_number',
            'grading_period' => 'required|in:prelims,midterms,prefinals,finals',
            'remarks' => 'nullable|string',
            'payment_method' => 'required|string',
            'ref_number' => 'nullable|string',
        ]);
        $gradingPeriod = $request->grading_period;
        $studentId = $request->student_id;
        $paymentAmount = $request->payment_amount;
        $orNumber = $request->or_number;
        $remarks = $request->remarks;
        $paymentMethod = $request->payment_method;
        $refNumber = $request->ref_number;

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

        $admission = Admission::where('student_id', $studentId)->first();

        // Update balance_due to the sum of the updated dues
        if ($paymentMethod === 'CASH') {
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
            $billing->balance_due = $billing->prelims_due
                + $billing->midterms_due
                + $billing->prefinals_due
                + $billing->finals_due;

            $billing->save();
        }
        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        } else {
            return redirect()->back()->withErrors(['error' => 'Admission record not found for this student.']);
        }
        // Update status in the Admission table

        $status = ($paymentMethod === 'CASH' ?  'completed' : 'pending');
        $payment = Payment::create([
            'student_id'        => $studentId,
            'school_year'       => $activeSchoolYear->name,
            'semester'          => $activeSchoolYear->semester,
            'grading_period'    => $gradingPeriod,
            'amount'            => $paymentAmount,
            'or_number'         => $orNumber,
            'remarks'           => $remarks,
            'payment_date'      => now(),
            'remaining_balance' => $billing->balance_due,
            'processed_by'      => Auth::id(),
            'payment_method'    => $paymentMethod,
            'ref_number'        => $refNumber,
        ]);
        $payment->status = $status;
        $payment->save();

        return redirect()->back()->with('success', 'Payment processed successfully!');
    }

    public function manualstore(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number'     => 'required|string|unique:payments,or_number',
            'grading_period' => 'required|in:prelims,midterms,prefinals,finals',
            'payment_date' => 'required|date',
            'remarks'       => 'nullable|string',
        ]);

        $gradingPeriod   = $request->grading_period;
        $studentId       = $request->student_id;
        $paymentAmount   = $request->payment_amount;
        $orNumber        = $request->or_number;
        $remarks         = $request->remarks;
        $manualDate = $request->payment_date;


        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return redirect()->back()->withErrors(['error' => 'No active school year found.']);
        }

        $billing = Billing::where('student_id', $studentId)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if (!$billing) {
            return redirect()->back()->withErrors(['error' => 'Billing record not found for this student.']);
        }

        if ($paymentAmount > $billing->balance_due) {
            return redirect()->back()->with('error', 'The payment amount cannot be greater than the current balance due.');
        }

        $dues = ['prelims_due', 'midterms_due', 'prefinals_due', 'finals_due'];
        $remainingPayment = $paymentAmount;

        foreach ($dues as $due) {
            if ($remainingPayment <= 0) break;

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

        $billing->balance_due = $billing->prelims_due
            + $billing->midterms_due
            + $billing->prefinals_due
            + $billing->finals_due;
        $billing->save();

        $admission = Admission::where('student_id', $studentId)->first();

        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        } else {
            return redirect()->back()->withErrors(['error' => 'Admission record not found for this student.']);
        }

        Payment::create([
            'student_id'        => $studentId,
            'school_year'       => $activeSchoolYear->name,
            'semester'          => $activeSchoolYear->semester,
            'grading_period'    => $gradingPeriod,
            'amount'            => $paymentAmount,
            'or_number'         => $orNumber,
            'remarks'           => $remarks,
            'payment_date'      => $manualDate,  // ← uses manual date
            'remaining_balance' => $billing->balance_due,
            'processed_by'      => Auth::id(), // Set current user
        ]);

        return redirect()->back()->with('success', 'Manual payment processed successfully!');
    }

    public function input(Request $request)
    {
        try {

            $request->validate([
                'student_id'   => 'required_if:student_type,1|nullable|exists:billings,student_id',
                'student_lrn'  => 'required_if:student_type,2|nullable|exists:shs_billings,student_lrn',
                'payment_amount' => 'required|numeric|min:0.01',
                'or_number'      => 'required|string|unique:payments,or_number',
                'remarks'        => 'nullable|string',
                'payment_type'   => 'nullable|string',
                'student_type'   => 'required|in:1,2',
            ]);

            $studentId = $request->student_id;
            $paymentAmount = $request->payment_amount;
            $orNumber = $request->or_number;
            $remarks = $request->remarks;
            $paymentType = $request->payment_type ?? 'others';
            $studentType = $request->student_type; // 1 for college, 2 for SHS
            $studentLrn = $request->student_lrn;
            if ($paymentType === 'others') {
                $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

                if (!$activeSchoolYear) {
                    return redirect()->back()->withErrors(['error' => 'No active school year found.']);
                }


                if ($studentType == 1) {
                    $studentLrn = null;
                } else {
                    $studentId = null;
                }
                OtherPayment::create([
                    'student_id'    => $studentId ?? null,
                    'lrn_number'    => $studentLrn ?? null,
                    'school_year'   => $activeSchoolYear->name,
                    'semester'      => $activeSchoolYear->semester,
                    'amount'        => $paymentAmount,
                    'or_number'     => $orNumber,
                    'remarks'       => $remarks,
                    'payment_type'  => $paymentType,
                    'payment_date'  => now(),
                    'processed_by'  => Auth::id(), // Current user ID
                ]);
                return redirect()->back()->with('success', 'Payment recorded successfully!');
            }
            // Fetch the active school year
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return redirect()->back()->withErrors(['error' => 'No active school year found.']);
            }

            // Log the payment (no billing update logic here)

            Payment::create([
                'student_id'    => $studentId,
                'school_year'   => $activeSchoolYear->name,
                'semester'      => $activeSchoolYear->semester,
                'amount'        => $paymentAmount,
                'or_number'     => $orNumber,
                'remarks'       => $remarks,
                'payment_type'  => $paymentType,
                'payment_date'  => now(),
                'processed_by'  => Auth::id(), // Current user ID
            ]);

            return redirect()->back()->with('success', 'Payment recorded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error processing payment: ' . $e->getMessage()]);
        }
    }
    public function manualinput(Request $request)
    {
        $request->validate([
            'student_id'     => 'required|exists:billings,student_id',
            'payment_amount' => 'required|numeric|min:0.01',
            'or_number'      => 'required|string|unique:payments,or_number',
            'remarks'        => 'nullable|string',
            'payment_type'   => 'nullable|string',
            'payment_date'   => 'nullable|date', // Allow manual date input
        ]);

        $studentId     = $request->student_id;
        $paymentAmount = $request->payment_amount;
        $orNumber      = $request->or_number;
        $remarks       = $request->remarks;
        $paymentType   = $request->payment_type ?? 'others';
        $paymentDate   = $request->payment_date ?? now(); // Use provided date or default to now

        // Fetch the active school year
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return redirect()->back()->withErrors(['error' => 'No active school year found.']);
        }

        // Log the payment
        Payment::create([
            'student_id'    => $studentId,
            'school_year'   => $activeSchoolYear->name,
            'semester'      => $activeSchoolYear->semester,
            'amount'        => $paymentAmount,
            'or_number'     => $orNumber,
            'remarks'       => $remarks,
            'payment_type'  => $paymentType,
            'payment_date'  => $paymentDate,
            'processed_by'  => Auth::id(), // Set to current user
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
