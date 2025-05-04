<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class VPAdminSideBarController extends Controller
{
    // Main dashboard
    public function dashboard()
    
    {
        $schoolYears = SchoolYear::all();
        $trashedSchoolYears = SchoolYear::onlyTrashed()->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first(); // 👈 get the active one
    
        return view('vp_admin.vpadmin_db', compact('schoolYears', 'trashedSchoolYears', 'activeSchoolYear'));
       
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
        $schoolYears = SchoolYear::all();
        $trashedSchoolYears = SchoolYear::onlyTrashed()->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first(); // 👈 get the active one
    
        return view('vp_admin.term_config.term-config', compact('schoolYears', 'trashedSchoolYears', 'activeSchoolYear'));
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
