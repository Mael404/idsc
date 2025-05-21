<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresidentSidebarController extends Controller
{
    /**
     * Display the President's Dashboard link in the sidebar.
     */
    public function dashboard()
    {
        return view('president.president_db');
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
