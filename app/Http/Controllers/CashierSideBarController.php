<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class CashierSideBarController extends Controller
{

    public function dashboard()
    {
        return view('cashier.cashier_db');
    }


    public function reportOtherPayments()
    {
        $payments = Payment::where('payment_type', 'others')
            ->where('is_void', 0)   // only not voided
            ->with('student')
            ->get();

        return view('cashier.reports.other', compact('payments'));
    }


    public function processPayment()
    {
        $billings = Billing::with('student')->get()->map(function ($billing) {
            $billing->full_name = Str::title($billing->student->first_name . ' ' . $billing->student->last_name);
            $billing->school_year_semester = "{$billing->school_year} - {$billing->semester}";
            return $billing;
        });

        // Only show non-voided payments
        $payments = Payment::with('student')
            ->where('is_void', false)
            ->get();

        return view('cashier.payment.process', compact('billings', 'payments'));
    }

    /**
     * Show the list of payment reports.
     */
    public function reportsIndex()
    {
        $payments = Payment::with('student')
            ->where(function ($query) {
                $query->whereNull('payment_type')
                    ->orWhere('payment_type', '');
            })
            ->where('is_void', false) // Only show non-voided payments
            ->get();

        return view('cashier.reports.index', compact('payments'));
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

                // Billing logic
                $billing = \App\Models\Billing::where('student_id', $enrollment->student_id)
                    ->where('school_year', $activeSchoolYear->name)
                    ->where('semester', $activeSchoolYear->semester)
                    ->first();

                $enrollment->initial_payment = $billing ? $billing->initial_payment : 0.00;

                return $enrollment;
            });


        return view('cashier.payment.pending', compact('pendingEnrollments'));
    }

    public function confirmPending(Request $request, $id)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        // Find the enrollment record
        $enrollment = Enrollment::where('id', $id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'Enrollment not found or does not match active school year.');
        }

        // Update enrollment status
        $enrollment->status = 'Enrolled';
        $enrollment->save();

        // Update admission status
        $admission = Admission::where('student_id', $enrollment->student_id)->first();
        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        }

        // Fetch the initial payment from billing
        $billing = Billing::where('student_id', $enrollment->student_id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if ($billing) {
            // Insert into payments table
            // Insert into payments table
            Payment::create([
                'student_id'   => $enrollment->student_id,
                'school_year'  => $activeSchoolYear->name,
                'semester'     => $activeSchoolYear->semester,
                'amount'       => $billing->initial_payment ?? 0,
                'remarks'      => $request->input('remarks'),
                'payment_date' => now(),
                'or_number'    => $request->input('or_number'),
            ]);
        }

        return back()->with('success', 'Student enrollment and payment recorded successfully!');
    }

    public function otherPayments()
    {
        $payments = Payment::where('payment_type', 'others')->with('student')->get();

        return view('cashier.payment.other', compact('payments'));
    }
}
