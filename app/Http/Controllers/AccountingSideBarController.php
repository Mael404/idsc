<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingSideBarController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function dashboard()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYearName = $activeSchoolYear->name ?? null;
        $semester = $activeSchoolYear->semester ?? null;

        $totalInitialFees = Billing::where('school_year', $schoolYearName)
            ->where('semester', $semester)
            ->sum('initial_payment');

        $outstandingBalances = Billing::where('school_year', $schoolYearName)
            ->where('semester', $semester)
            ->sum('balance_due');

        $recentPayments = Payment::orderBy('payment_date', 'desc')->take(5)->get();
        $fullPaymentsCount = Billing::where('is_full_payment', true)->count();

        $balanceDistributionData = Billing::selectRaw('MONTHNAME(created_at) as month, SUM(balance_due) as outstanding, SUM(tuition_fee - balance_due) as collected')
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%M') ASC")
            ->get();

        $paymentSourcesData = Payment::selectRaw('remarks, COUNT(*) as count')
            ->groupBy('remarks')
            ->get();

        // 📊 Program-wise Initial Payments and Balances
        $programFinancials = DB::table('billings')
            ->join(DB::raw('enrollments'), DB::raw('CONVERT(billings.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci'), '=', DB::raw('enrollments.student_id'))
            ->join('program_course_mappings', 'enrollments.course_mapping_id', '=', 'program_course_mappings.id')
            ->join('programs', 'program_course_mappings.program_id', '=', 'programs.id')
            ->where('billings.school_year', $schoolYearName)
            ->where('billings.semester', $semester)
            ->select(
                'programs.name as program_name',
                DB::raw('SUM(billings.initial_payment) as total_initial_payment'),
                DB::raw('SUM(billings.balance_due) as total_balance_due')
            )
            ->groupBy('programs.name')
            ->get();


        return view('accountant.accountant_db', [
            'totalInitialFees' => $totalInitialFees,
            'outstandingBalances' => $outstandingBalances,
            'recentPayments' => $recentPayments,
            'fullPaymentsCount' => $fullPaymentsCount,
            'balanceDistributionData' => $balanceDistributionData,
            'paymentSourcesData' => $paymentSourcesData,
            'programFinancials' => $programFinancials, // 👈 pass to view
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
