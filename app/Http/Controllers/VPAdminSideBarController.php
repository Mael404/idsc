<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\OtherFee;
use App\Models\Program;
use App\Models\ProgramCourseMapping;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use Illuminate\Http\Request;

class VPAdminSideBarController extends Controller
{
    // Main dashboard
    public function dashboard()
    {
        // Get school years and trashed ones
        $schoolYears = SchoolYear::all();
        $trashedSchoolYears = SchoolYear::onlyTrashed()->get();

        // Get the current active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        // If no active school year, pass empty collections and nulls
        if (!$activeSchoolYear) {
            return view('vp_admin.vpadmin_db', [
                'schoolYears' => $schoolYears,
                'trashedSchoolYears' => $trashedSchoolYears,
                'activeSchoolYear' => null,
                'enrollmentData' => collect(),
                'programs' => Program::all(),
                'yearLevels' => YearLevel::orderBy('id')->get(),
                'topUnpaid' => collect(),
            ]);
        }

        // Enrollment Heatmap Data
        $enrollmentData = Enrollment::with('courseMapping.program', 'courseMapping.yearLevel')
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
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
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->where('balance_due', '>=', 10000)
            ->orderByDesc('balance_due')
            ->take(10)
            ->get();

        // Additional shared data
        $programs = Program::all();
        $yearLevels = YearLevel::orderBy('id')->get();

        return view('vp_admin.vpadmin_db', compact(
            'schoolYears',
            'trashedSchoolYears',
            'activeSchoolYear',
            'enrollmentData',
            'programs',
            'yearLevels',
            'topUnpaid'
        ));
    }

    // Blank page
    public function blankPage()
    {
        return view('vp_admin.vpadmin_blank');
    }
    public function otherFees()
    {
        $fees = OtherFee::all();
        $trashedFees = OtherFee::onlyTrashed()->get();
        return view('vp_admin.fees.other_fees', compact('fees', 'trashedFees'));
      
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
