<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Admission;

class ReportGenerationController extends Controller
{
    /**
     * Display a list of all payment reports.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all payments with student information
        $payments = Payment::with('student')->get();

        return view('reports.index', compact('payments'));
    }

    /**
     * Generate a report based on filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function generate(Request $request)
    {
        $filters = $request->only(['student_id', 'payment_date']);

        // Filter payments based on the request inputs
        $query = Payment::with('student');

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['payment_date'])) {
            $query->whereDate('payment_date', $filters['payment_date']);
        }

        $filteredPayments = $query->get();

        return view('reports.filtered', compact('filteredPayments'));
    }
}
