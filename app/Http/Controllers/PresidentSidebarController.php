<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Program;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresidentSidebarController extends Controller
{
    /**
     * Display the President's Dashboard link in the sidebar.
     */
    public function dashboard()
    {
        // Get the current active school year
        $activeSY = SchoolYear::where('is_active', 1)->first();

        if (!$activeSY) {
            return view('president.president_db', [
                'enrollmentData' => collect(),
                'programs' => Program::all(),
                'yearLevels' => YearLevel::orderBy('id')->get(),
                'topUnpaid' => collect(),
                'activeSY' => null,
            ]);
        }

        // Enrollment Heatmap Data
        $enrollmentData = Enrollment::with('courseMapping.program', 'courseMapping.yearLevel')
            ->where('school_year', $activeSY->name)
            ->where('semester', $activeSY->semester)
            ->get()
            ->groupBy(function ($enrollment) {
                return $enrollment->courseMapping->program->name ?? 'Unknown';
            })
            ->map(function ($group) {
                return $group->groupBy(function ($enrollment) {
                    return $enrollment->courseMapping->yearLevel->name ?? 'Unknown';
                })->map->count();
            });

        // Top 10 Students with ₱10,000+ Unpaid Balances
        $topUnpaid = Billing::with('student')
            ->where('school_year', $activeSY->name)
            ->where('semester', $activeSY->semester)
            ->where('balance_due', '>=', 10000)
            ->orderByDesc('balance_due')
            ->take(10)
            ->get();

        $programs = Program::all();
        $yearLevels = YearLevel::orderBy('id')->get();

        return view('president.president_db', compact(
            'enrollmentData',
            'programs',
            'yearLevels',
            'topUnpaid',
            'activeSY'
        ));
    }

    public function accounting()
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

        return view('president.accounting-dashboard', [
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
     * Display the Revenue Trends section.
     */
    public function revenueTrends()
    {
        return view('president.revenue_trends');
    }

    /**
     * Display the Scholarships and Discounts section.
     */
    public function scholarshipsDiscounts()
    {
        return view('president.scholarships_discounts');
    }

    /**
     * Display the Enrollment Heatmap section.
     */
    public function enrollmentHeatmap()
    {
        return view('president.enrollment_heatmap');
    }

    /**
     * Display the Financial Alerts section.
     */
    public function financialAlerts()
    {
        return view('president.financial_alerts');
    }
}
