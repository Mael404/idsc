<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VPAdminSideBarController extends Controller
{
    // Main dashboard
    public function dashboard()
    {
        return view('vp_admin.vpadmin_db');
    }

    // Blank page
    public function blankPage()
    {
        return view('vp_admin.vpadmin_blank');
    }

    // Fees
    public function editTuition()
    {
        return view('vp_admin.fees.edit_tuition');
    }

    public function miscFees()
    {
        return view('vp_admin.fees.misc_fees');
    }

    // Academic
    public function termConfiguration()
    {
        return view('vp_admin.academic.term_configuration');
    }

    // User Management
    public function addNewUser()
    {
        return view('vp_admin.user_management.add_new');
    }

    public function manageUsers()
    {
        return view('vp_admin.user_management.manage');
    }

    public function activateUsers()
    {
        return view('vp_admin.user_management.activate');
    }
}
