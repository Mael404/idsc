<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VPAcademicsSideBarController extends Controller
{
    // VP ACADEMICS --------------------
    public function vpAdminDashboard()
    {
        return view('vp_academic.vpacademic_db');
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
        return view('vp_academic.course_management.vpacademic_programs');
    }

    public function courses()
    {
        return view('vp_academic.course_management.vpacademic_courses');
    }
}
