<?php

namespace App\Http\Controllers;

use App\Models\ProgramCourseMapping;
use App\Models\Scholarship;
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
        // Fetch all scholarships including trashed ones
        $scholarships = Scholarship::all(); // Only active
        $trashedScholarships = Scholarship::onlyTrashed()->get(); // Trashed for modal

        // Return the view with both active and trashed scholarships
        return view('vp_admin.fees.scholarship', compact('scholarships', 'trashedScholarships'));
    }


    public function miscFees()
    {
        $schoolYears = SchoolYear::withTrashed()->get();

        $groupedMappings = ProgramCourseMapping::with(['program', 'course', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return [
                    'program_name' => $group->first()->program->name,
                    'year_level' => $group->first()->yearLevel->name,
                    'semester' => $group->first()->semester->name ?? 'N/A',
                    'effective_sy' => $group->first()->effective_sy,
                    'courses' => $group->pluck('course.name')->implode(', '),
                    'mapping_ids' => $group->pluck('id')->toArray(),
                ];
            });

        return view('vp_admin.fees.misc-fees', compact('groupedMappings'));
    }



    public function termConfiguration()
    {
        $schoolYears = SchoolYear::all();
        $trashedSchoolYears = SchoolYear::onlyTrashed()->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

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
