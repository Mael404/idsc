<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class AccountingSideBarController extends Controller
{
    /**
     * Show the dashboard page.
     */
     public function dashboard()
    {
        // Fetch total tuition fees
        $totalTuitionFees = Billing::sum('tuition_fee');

        // Fetch outstanding balances
        $outstandingBalances = Billing::sum('balance_due');

        // Fetch recent payments (last 5 transactions)
        $recentPayments = Payment::orderBy('payment_date', 'desc')->take(5)->get();

        // Count fully paid students
        $fullPaymentsCount = Billing::where('is_full_payment', true)->count();

        // Fetch data for Balance Distribution chart
        $balanceDistributionData = Billing::selectRaw('MONTHNAME(created_at) as month, SUM(balance_due) as outstanding, SUM(tuition_fee - balance_due) as collected')
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%M')")
            ->get();

        // Fetch data for Payment Sources chart
        $paymentSourcesData = Payment::selectRaw('remarks, COUNT(*) as count')
            ->groupBy('remarks')
            ->get();

        return view('accountant.accountant_db', [
            'totalTuitionFees' => $totalTuitionFees,
            'outstandingBalances' => $outstandingBalances,
            'recentPayments' => $recentPayments,
            'fullPaymentsCount' => $fullPaymentsCount,
            'balanceDistributionData' => $balanceDistributionData,
            'paymentSourcesData' => $paymentSourcesData,
        ]);
    }


    /**
     * Show the transactions page.
     */
    public function transactions()
    {
        // Fetch payments and admissions
        $payments = \App\Models\Payment::all();

        // Fetch all necessary admissions and related data
        $admissions = \App\Models\Admission::with(['programCourseMapping.program'])->get();

        return view('accountant.transactions', compact('payments', 'admissions'));
    }



    /**
     * Show the Statement of Account (SOA) page.
     */
    public function soa()
    {
          // Fetch admissions and scholarships
        $admissions = Admission::with(['programCourseMapping.program'])->orderBy('created_at')->get();
        $scholarships = Scholarship::all();

        return view('accountant.soa', compact('admissions', 'scholarships'));

     
        // Fetch any necessary data for SOA here.
       
    }

    /**
     * Show the student ledger page.
     */
    public function studentLedger()
    {
        // Fetch any necessary data for the student ledger here.
        return view('student-ledger');
    }

    /**
     * Show the promisories page.
     */
    public function promisories()
    {
        // Fetch any necessary data for promisories here.
        return view('promisories');
    }
}
