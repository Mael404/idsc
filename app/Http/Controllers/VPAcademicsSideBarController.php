<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class VPAcademicsSideBarController extends Controller
{
    // VP ACADEMICS --------------------
    public function vpAdminDashboard()
    {
        
        $activeSchoolYear = SchoolYear::where('is_active', true)->first(); // 👈 get the active one
    
        return view('vp_academic.vpacademic_db', compact( 'activeSchoolYear'));
    }

    // Scheduling Routes --------------------
    public function roomAssignment()
    {
        return view('scheduling.room_assignment');
    }

    public function facultyLoad()
    {
        return view('scheduling.faculty_load');
    }

    public function scheduleClasses()
    {
        return view('scheduling.schedule_classes');
    }

    // Faculty Evaluation Radar --------------------
    public function evaluationRadar()
    {
        return view('faculty.evaluation_radar');
    }

    // Analytics --------------------
    public function analytics()
    {
        return view('analytics.index');
    }

    // Course Management --------------------
    public function programs()
    {
        return view('vp_academic.course_management.programs');
    }

    public function courses()
    {
        return view('vp_academic.course_management.courses');
    }
}
