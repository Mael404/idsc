<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CashierSideBarController extends Controller
{
    /**
     * Show the cashier dashboard.
     */
    public function dashboard()
    {
        return view('cashier.cashier_db');
    }

    /**
     * Show the payment processing page.
     */
    public function processPayment()

    {
        $billings = Billing::with('student')->get()->map(function ($billing) {
            $billing->full_name = Str::title($billing->student->first_name . ' ' . $billing->student->last_name);
            $billing->school_year_semester = "{$billing->school_year} - {$billing->semester}";
            return $billing;
        });
        return view('cashier.payment.process', compact('billings'));
    }

    /**
     * Show the list of payment reports.
     */
    public function reportsIndex()
    {
          $payments = Payment::with('student')->get();
        return view('cashier.reports.index' ,compact('payments'));
    }
}
