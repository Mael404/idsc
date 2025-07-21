<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // You can add logic here, like fetching summary info for dashboard
        return view('cashier.cashier_db');
    }
}
