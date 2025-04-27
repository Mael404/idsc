<?php

namespace App\Http\Controllers;

use App\Models\ProgramCourseMapping;
use App\Models\Course;
use App\Models\Program;
use App\Models\YearLevel;
use App\Models\Semester;
use Illuminate\Http\Request;

class ProgramCourseMappingController extends Controller
{
    // Show the program mapping view with dropdown valuess
    public function index()
    {
        $courses = Course::all();
        $programs = Program::all();
        $yearLevels = YearLevel::all();
        $semesters = Semester::all();
    
        // Group program mappings by Program, Year Level, and Semester
        $programMappings = ProgramCourseMapping::with(['course', 'program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id; // Group by Program, Year Level, and Semester
            });
    
        return view('vp_academic.course_management.program-mapping', compact('courses', 'programs', 'yearLevels', 'semesters', 'programMappings'));
    }
    

    // Store a new program course mapping
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'course_id' => 'required|array',  // Ensure the course_id is an array
            'course_id.*' => 'exists:courses,id',  // Ensure each course ID exists in the courses table
            'program_id' => 'required|exists:programs,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);
    
        // Loop through each selected course
        foreach ($request->course_id as $course_id) {
            // Check if the program-course mapping already exists
            $existingMapping = ProgramCourseMapping::where('course_id', $course_id)
                ->where('program_id', $request->program_id)
                ->where('year_level_id', $request->year_level_id)
                ->where('semester_id', $request->semester_id)
                ->first();
    
            // If mapping exists, skip this course, or you can show an error for this specific course
            if ($existingMapping) {
                continue; // Optionally you can add logic to return an error for this course if needed
            }
    
            // Create the new program-course mapping
            ProgramCourseMapping::create([
                'course_id' => $course_id,
                'program_id' => $request->program_id,
                'year_level_id' => $request->year_level_id,
                'semester_id' => $request->semester_id,
            ]);
        }
    
        return redirect()->route('program.mapping.index')->with('success', 'Program Mappings created successfully!');
    }
    
    
}
