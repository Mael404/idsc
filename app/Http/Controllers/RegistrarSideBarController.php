<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\ProgramCourseMapping;
use Illuminate\Http\Request;

class RegistrarSideBarController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('registrar.registrar_db');
    }

    // Records
    public function quickSearch()
    {
        $admissions = Admission::latest()->get();

        // Group by the unique combination for display
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first(); // pick one representative from the group
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        $allCourses = \App\Models\Course::orderBy('code')->get();

        return view('registrar.enrollment.enrollment', compact('admissions', 'courseMappings', 'allCourses')); // ✅ Add here
    }   

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
