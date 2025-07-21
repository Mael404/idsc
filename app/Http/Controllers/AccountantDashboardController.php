<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Http\Request;

class AccountantDashboardController extends Controller
{
    public function index()
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
}
