<?php

namespace App\Http\Controllers;

use App\Models\OldAccount;
use App\Models\OldAccPayment;
use App\Models\OtherPayment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OldAccountPaymentController extends Controller
{

    public function voidPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:old_acc_payments,id',
        ]);

        try {
            $payment = OldAccPayment::findOrFail($request->payment_id);

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
    public function input(Request $request)
    {
        try {
            // dd($request->all());
            // Log::info('Uniform Payment Input:', $request->all());
            $request->validate([
                'student_id'   => 'required|exists:old_accounts,id',
                'payment_amount' => 'required|numeric|min:0.01',
                'or_number' => 'required|string|unique:old_acc_payments,or_number',
            ]);
            $paymentAmount = $request->payment_amount;
            $or_number = $request->or_number;
            $id = $request->student_id;
            // Fetch the active school year
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return redirect()->back()->withErrors(['error' => 'No active school year found.']);
            }
            $user = Auth::user();
            // dd($user);
            OldAccPayment::create([
                'student_id'    => $id,
                'amount'        => $paymentAmount,
                'or_number'     => $or_number,
                'payment_date'  => now(),
                'processed_by'  => $user->id,
                'particulars'   => 'n/a'
            ]);

            return redirect()->back()->with('success', 'Payment recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Uniform Payment Error:', [
                'request' => $request->all(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()]);
        }
    }
}
