<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Admission;

class AccountantTransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all payments
        $payments = Payment::all();

        // Fetch all admissions for cross-referencing student details
        $admissions = Admission::all();

        return view('accountant.transactions.index', compact('payments', 'admissions'));
    }

    /**
     * Show a specific transaction.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Fetch specific payment
        $payment = Payment::findOrFail($id);

        // Fetch student details from admissions
        $admission = Admission::where('student_id', $payment->student_id)->first();

        return view('accountant.transactions.show', compact('payment', 'admission'));
    }
}
