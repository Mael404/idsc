<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\ProgramCourseMapping;
use App\Models\RefRegion;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class RegistrarSideBarController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('registrar.registrar_db');
    }

    // Enrollment
    public function newEnrollment()
    {

        // Fetch all admissions with course mapping relationships
        $admissions = Admission::with([
            'courseMapping.program',
            'courseMapping.yearLevel',
            'courseMapping.semester'
        ])->get();

        // Get unique and sorted course mappings
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        // Sample mapping ID just to pass along if needed
        $sampleMappingId = optional($courseMappings->first())->id;

        // Get all courses
        $allCourses = \App\Models\Course::orderBy('id')->get();

        // Get active scholarships
        $scholarships = Scholarship::where('status', 1)->orderBy('name')->get();

        // Get selected mapping ID from request
        $selectedMappingId = request('selected_mapping_id');

        // Default total units
        $totalUnits = 0;

        // If a mapping is selected, calculate total course units
        if ($selectedMappingId) {
            // Get the selected mapping
            $selectedMapping = ProgramCourseMapping::find($selectedMappingId);

            if ($selectedMapping) {
                // Find all mappings with same program, year, sem, and SY
                $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
                    ->where('year_level_id', $selectedMapping->year_level_id)
                    ->where('semester_id', $selectedMapping->semester_id)
                    ->where('effective_sy', $selectedMapping->effective_sy)
                    ->get();

                // Extract all course_ids
                $courseIds = $matchingMappings->pluck('course_id')->unique();

                // Sum course units from Course table
                $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
            }
        }
   $regions = RefRegion::with('provinces.cities.barangays')->get();
        // Pass all data to view
        return view('registrar.enrollment.enrollment', compact(
            'admissions',
            'courseMappings',
            'allCourses',
            'scholarships',
            'sampleMappingId',
            'selectedMappingId',
            'regions',
            'totalUnits'
        ));
    }




    public function transfereeEnrollment()
    {
        return view('registrar.enrollment.transferee_enrollment'); // View for transferees
    }

    public function reEnrollRegular()
    {
        $admissions = Admission::latest()->get();

        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        $allCourses = \App\Models\Course::orderBy('code')->get();

        return view('registrar.enrollment.re_enroll_regular', compact('admissions', 'courseMappings', 'allCourses'));
    }

    public function reEnrollIrregular()
    {
        return view('registrar.enrollment.re_enroll_irregular'); // View for irregular re-enrollment
    }


    public function enrollmentRecords()
    {
        return view('registrar.enrollment.records'); // Table view by student/term
    }

    // Records
    public function quickSearch() {}

    public function bulkUpload()
    {
        return view('registrar.records.bulk_upload');
    }

    // Requests
    public function expressProcessing()
    {
        return view('registrar.requests.express_processing');
    }

    public function notifyStudent()
    {
        return view('registrar.requests.notify_student');
    }

    // Archive
    public function oldStudentRecords()
    {
        return view('registrar.archive.old_student_records');
    }

    public function disposalLog()
    {
        return view('registrar.archive.disposal_log');
    }
}
