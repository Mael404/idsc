<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\UniformPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UniformPaymentController extends Controller
{
    //
    public function input(Request $request)
    {
        try {
            // Log::info('Uniform Payment Input:', $request->all());
            $request->validate([
                'student_id'   => 'required_if:student_type,1|nullable|exists:billings,student_id',
                'student_lrn'  => 'required_if:student_type,2|nullable|exists:shs_billings,student_lrn',
                'payment_amount' => 'required|numeric|min:0.01',
                'trans_no' => 'required|string|unique:uniform_payments,trans_no',
                'student_type'   => 'required|in:1,2',
            ]);

            $studentId = $request->student_id;
            $paymentAmount = $request->payment_amount;
            $trans_no = $request->trans_no;
            $studentType = $request->student_type; // 1 for college, 2 for SHS
            $studentLrn = $request->student_lrn;
            // Fetch the active school year
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return redirect()->back()->withErrors(['error' => 'No active school year found.']);
            }

            if ($studentType == 1) {
                $studentLrn = null;
            } else {
                $studentId = null;
            }
            UniformPayment::create([
                'student_id'    => $studentId ?? null,
                'lrn_number'    => $studentLrn ?? null,
                'school_year'   => $activeSchoolYear->name,
                'semester'      => $activeSchoolYear->semester,
                'amount'        => $paymentAmount,
                'trans_no'     => $trans_no,
                'payment_date'  => now(),
                'processed_by'  => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Payment recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Uniform Payment Error:', [
                'request' => $request->all(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()]);
        }
    }
}
