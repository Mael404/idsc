<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\OtherFee;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualCashierController extends Controller
{
    public function dashboard()
    {
        return view('manual_cashier.cashier_db');
    }

   public function reportOtherPayments()
{
    $payments = Payment::where('payment_type', 'others')
        ->where('is_void', 0)
        ->where('status', '!=', 'void_approved')
        ->where('processed_by', Auth::id()) 
        ->with('student')
        ->get();

    return view('manual_cashier.reports.other', compact('payments'));
}

    public function processPayment()
    {
        $billings = Billing::with('student')->get()->map(function ($billing) {
            $billing->full_name = Str::title($billing->student->first_name . ' ' . $billing->student->last_name);
            $billing->school_year_semester = "{$billing->school_year} - {$billing->semester}";
            return $billing;
        });

        $payments = Payment::with('student')
            ->where('is_void', false)
            ->get();

        return view('manual_cashier.payment.process', compact('billings', 'payments'));
    }

  public function reportsIndex()
{
    $payments = Payment::with('student')
        ->where(function ($query) {
            $query->whereNull('payment_type')
                  ->orWhere('payment_type', '');
        })
        ->where('is_void', false)
        ->where('status', '!=', 'void_approved')
        ->where('processed_by', Auth::id()) // ✅ Only show payments processed by current user
        ->get();

    return view('manual_cashier.reports.index', compact('payments'));
}
    public function pendingEnrollments()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        $pendingEnrollments = Enrollment::with(['admission'])
            ->where('status', 'Pending')
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get()
            ->map(function ($enrollment) use ($activeSchoolYear) {
                $admission = $enrollment->admission;

                $enrollment->first_name = optional($admission)->first_name;
                $enrollment->last_name = optional($admission)->last_name;
                $enrollment->middle_name = optional($admission)->middle_name;

                $middleInitial = $enrollment->middle_name
                    ? strtoupper(substr($enrollment->middle_name, 0, 1)) . '.'
                    : '';

                $enrollment->full_name = Str::title(
                    "{$enrollment->last_name}, {$enrollment->first_name} {$middleInitial}"
                );

                $billing = Billing::where('student_id', $enrollment->student_id)
                    ->where('school_year', $activeSchoolYear->name)
                    ->where('semester', $activeSchoolYear->semester)
                    ->first();

                $enrollment->initial_payment = $billing ? $billing->initial_payment : 0.00;

                return $enrollment;
            });

        return view('manual_cashier.payment.pending', compact('pendingEnrollments'));
    }

    public function confirmPending(Request $request, $id)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        $enrollment = Enrollment::where('id', $id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'Enrollment not found or does not match active school year.');
        }

        $enrollment->status = 'Enrolled';
        $enrollment->save();

        $admission = Admission::where('student_id', $enrollment->student_id)->first();
        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        }

        $billing = Billing::where('student_id', $enrollment->student_id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if ($billing) {
            Payment::create([
                'student_id'    => $enrollment->student_id,
                'school_year'   => $activeSchoolYear->name,
                'semester'      => $activeSchoolYear->semester,
                'amount'        => $billing->initial_payment ?? 0,
                'remarks'       => $request->input('remarks'),
                'payment_date'  => now(),
                'or_number'     => $request->input('or_number'),
                'processed_by'  => Auth::id(), 
            ]);
        }

        return back()->with('success', 'Student enrollment and payment recorded successfully!');
    }

    public function otherPayments()
    {
        $payments = Payment::where('payment_type', 'others')->with('student')->get();
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $otherFees = OtherFee::all();

        return view('manual_cashier.payment.other', compact('payments', 'activeSchoolYear', 'otherFees'));
    }
}
