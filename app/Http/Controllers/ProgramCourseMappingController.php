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
    // Show the program mapping view with dropdown values
    public function index()
    {
        $courses = Course::all();
        $programs = Program::all();
        $yearLevels = YearLevel::all();
        $semesters = Semester::all();
    
        // Group program mappings by Program, Year Level, Semester, and Effective SY
        $programMappings = ProgramCourseMapping::with(['course', 'program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                // Group by Program, Year Level, Semester, and Effective SY
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            });
    
        return view('vp_academic.course_management.program-mapping', compact('courses', 'programs', 'yearLevels', 'semesters', 'programMappings'));
    }
    

    // Store a new program course mapping
    public function store(Request $request)
    {
        // Validate incoming request, including the effective_sy field
        $request->validate([
            'course_id' => 'required|array',  // Ensure the course_id is an array
            'course_id.*' => 'exists:courses,id',  // Ensure each course ID exists in the courses table
            'program_id' => 'required|exists:programs,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'semester_id' => 'required|exists:semesters,id',
            'effective_sy' => 'required|string',  // Validate the effective_sy field (school year)
        ]);
    
        // Check if the program with the same program_id and effective_sy already exists
        $existingMapping = ProgramCourseMapping::where('program_id', $request->program_id)
            ->where('effective_sy', $request->effective_sy)
            ->first();
    
        // If mapping exists, return a message and prevent further submission
        if ($existingMapping) {
            return redirect()->back()->with('error', 'This program mapping already exists for the given school year. You can edit or update it.');
        }
    
        // Loop through each selected course
        foreach ($request->course_id as $course_id) {
            // Check if the program-course mapping already exists
            $existingMapping = ProgramCourseMapping::where('course_id', $course_id)
                ->where('program_id', $request->program_id)
                ->where('year_level_id', $request->year_level_id)
                ->where('semester_id', $request->semester_id)
                ->where('effective_sy', $request->effective_sy)  // Check for the same effective_sy
                ->first();
    
            // If mapping exists, skip this course
            if ($existingMapping) {
                continue;
            }
    
            // Create the new program-course mapping
            ProgramCourseMapping::create([
                'course_id' => $course_id,
                'program_id' => $request->program_id,
                'year_level_id' => $request->year_level_id,
                'semester_id' => $request->semester_id,
                'effective_sy' => $request->effective_sy,  // Save the effective_sy value
            ]);
        }
    
        return redirect()->route('program.mapping.index')->with('success', 'Program Mappings created successfully!');
    }
    
}
