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
        // Fetch admissions data
        $admissions = $this->getAdmissionsForActiveSchoolYear();

        // Fetch course mappings
        $courseMappings = $this->getUniqueSortedCourseMappings();

        // Fetch all courses
        $allCourses = $this->getAllCourses();

        // Fetch scholarships
        $scholarships = $this->getActiveScholarships();

        // Calculate total units if a mapping is selected
        $selectedMappingId = request('selected_mapping_id');
        $totalUnits = $this->calculateTotalUnits($selectedMappingId);

        // Fetch regions
        $regions = $this->getRegions();

        // Pass all data to view
        return view('registrar.enrollment.enrollment', compact(
            'admissions',
            'courseMappings',
            'allCourses',
            'scholarships',
            'selectedMappingId',
            'regions',
            'totalUnits'
        ));
    }

    private function getAdmissionsForActiveSchoolYear()
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['error' => 'No active school year found'], 404);
        }

        return Admission::with([
            'courseMapping.program',
            'courseMapping.yearLevel',
            'courseMapping.semester',
            'billing'
        ])->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get();
    }

    private function getUniqueSortedCourseMappings()
    {
        return ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');
    }

    private function getAllCourses()
    {
        return \App\Models\Course::orderBy('id')->get();
    }

    private function getActiveScholarships()
    {
        return Scholarship::where('status', 1)->orderBy('name')->get();
    }

    private function calculateTotalUnits($selectedMappingId)
    {
        if (!$selectedMappingId) {
            return 0;
        }

        $selectedMapping = ProgramCourseMapping::find($selectedMappingId);

        if (!$selectedMapping) {
            return 0;
        }

        $matchingMappings = ProgramCourseMapping::where('program_id', $selectedMapping->program_id)
            ->where('year_level_id', $selectedMapping->year_level_id)
            ->where('semester_id', $selectedMapping->semester_id)
            ->where('effective_sy', $selectedMapping->effective_sy)
            ->get();

        $courseIds = $matchingMappings->pluck('course_id')->unique();

        return \App\Models\Course::whereIn('id', $courseIds)->sum('units');
    }
    private function getEnrollmentsForActiveSchoolYear()
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['error' => 'No active school year found'], 404);
        }

        return \App\Models\Enrollment::with([
            'courseMapping.program',
            'courseMapping.yearLevel',
            'courseMapping.semester',
            'billing',
            'admission'
        ])->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get();
    }


    private function getRegions()
    {
        return RefRegion::with('provinces.cities.barangays')->get();
    }


    public function transfereeEnrollment()
    {
        return view('registrar.enrollment.transferee_enrollment'); // View for transferees
    }

    public function reEnrollRegular()
    {
        // Fetch admissions data with enrollment_type = 'old'
        $enrollments = $this->getEnrollmentsForActiveSchoolYear()->where('enrollment_type', 'old');

        // Fetch course mappings
        $courseMappings = $this->getUniqueSortedCourseMappings();

        // Fetch all courses
        $allCourses = $this->getAllCourses();

        // Fetch scholarships
        $scholarships = $this->getActiveScholarships();

        // Calculate total units if a mapping is selected
        $selectedMappingId = request('selected_mapping_id');
        $totalUnits = $this->calculateTotalUnits($selectedMappingId);

        // Fetch regions
        $regions = $this->getRegions();

        // Pass all data to view
        return view('registrar.enrollment.re_enroll_regular', compact(
            'enrollments',
            'courseMappings',
            'allCourses',
            'scholarships',
            'selectedMappingId',
            'regions',
            'totalUnits'
        ));
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

   public function editStudent($student_id)
{
    $admission = Admission::where('student_id', $student_id)->firstOrFail();
    $scholarships = Scholarship::all();
    $courseMappings = ProgramCourseMapping::all();
    
    // Get all regions for the dropdown
    $regions = RefRegion::all();
    
    return view('registrar.enrollment.edit-students', compact(
        'admission',
        'scholarships',
        'courseMappings',
        'regions'
    ));
}
}
