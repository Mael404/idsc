<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use Illuminate\Http\Request;

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
