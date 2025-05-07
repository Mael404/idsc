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
        $courses = Course::with('prerequisites')->get();
        $programs = Program::all();
        $yearLevels = YearLevel::all();
        $semesters = Semester::all();

        $programMappings = ProgramCourseMapping::with([
            'program',
            'course.prerequisites',
            'yearLevel',
            'semester'
        ])->get()->groupBy(function ($item) {
            return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
        });

        return view('vp_academic.course_management.program-mapping', compact(
            'courses',
            'programs',
            'yearLevels', 
            'semesters',
            'programMappings'
        ));
    }


    // Store a new program course mapping or update an existing one
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'program_id' => 'required|exists:programs,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'semester_id' => 'required|exists:semesters,id',
            'effective_sy' => 'required|string',
            'action_type' => 'required|string|in:add_course,create_mapping',
        ]);

        if ($request->action_type === 'create_mapping') {
            // Check if the full mapping group already exists
            $exists = ProgramCourseMapping::where('program_id', $request->program_id)
                ->where('year_level_id', $request->year_level_id)
                ->where('semester_id', $request->semester_id)
                ->where('effective_sy', $request->effective_sy)
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'This program mapping already exists. You can add more courses to it instead.');
            }
        }

        // Proceed to add courses
        foreach ($request->course_id as $course_id) {
            $exists = ProgramCourseMapping::where('program_id', $request->program_id)
                ->where('year_level_id', $request->year_level_id)
                ->where('semester_id', $request->semester_id)
                ->where('effective_sy', $request->effective_sy)
                ->where('course_id', $course_id)
                ->exists();

            if (!$exists) {
                ProgramCourseMapping::create([
                    'course_id' => $course_id,
                    'program_id' => $request->program_id,
                    'year_level_id' => $request->year_level_id,
                    'semester_id' => $request->semester_id,
                    'effective_sy' => $request->effective_sy,
                ]);
            }
        }

        return redirect()->route('program.mapping.index')->with('success', 'Course(s) successfully added.');
    }

    public function toggleActive($id)
    {
        $mapping = ProgramCourseMapping::findOrFail($id);
        $mapping->active = !$mapping->active;
        $mapping->save();

        return redirect()->back()->with('success', 'Mapping status updated.');
    }

    public function update(Request $request, $id)
    {
        $mapping = ProgramCourseMapping::findOrFail($id);

        $programId = $mapping->program_id;
        $yearLevelId = $mapping->year_level_id;
        $semesterId = $mapping->semester_id;
        $effectiveSy = $mapping->effective_sy;

        // 1. Handle Removal of Existing Courses
        $submittedExisting = $request->input('existing_courses', []);

        // Get all existing mappings for this group
        $existingMappings = ProgramCourseMapping::where([
            'program_id' => $programId,
            'year_level_id' => $yearLevelId,
            'semester_id' => $semesterId,
            'effective_sy' => $effectiveSy,
        ])->get();

        foreach ($existingMappings as $map) {
            if (!in_array($map->course_id, $submittedExisting)) {
                $map->delete(); // Remove courses that were removed from the UI
            }
        }

        // 2. Add New Courses
        if ($request->has('new_courses')) {
            foreach ($request->new_courses as $courseId) {
                // Avoid duplicates
                $exists = ProgramCourseMapping::where([
                    'program_id' => $programId,
                    'year_level_id' => $yearLevelId,
                    'semester_id' => $semesterId,
                    'effective_sy' => $effectiveSy,
                    'course_id' => $courseId,
                ])->exists();

                if (!$exists) {
                    ProgramCourseMapping::create([
                        'program_id' => $programId,
                        'year_level_id' => $yearLevelId,
                        'semester_id' => $semesterId,
                        'effective_sy' => $effectiveSy,
                        'course_id' => $courseId,
                        'active' => 1,
                    ]);
                }
            }
        }

        return back()->with('success', 'Program mapping updated successfully.');
    }


    // Remove a course from the program mapping
    public function destroy($id)
    {
        // Find the mapping by its ID
        $mapping = ProgramCourseMapping::findOrFail($id);

        // Retrieve the values to use for deletion
        $programId = $mapping->program_id;
        $yearLevelId = $mapping->year_level_id;
        $semesterId = $mapping->semester_id;
        $effectiveSy = $mapping->effective_sy;

        // Delete all rows with the same program_id, year_level_id, semester_id, and effective_sy
        ProgramCourseMapping::where([
            'program_id' => $programId,
            'year_level_id' => $yearLevelId,
            'semester_id' => $semesterId,
            'effective_sy' => $effectiveSy
        ])->delete();

        // Redirect back with a success message
        return redirect()->route('program.mapping.index')->with('success', 'Program mapping and associated courses deleted successfully.');
    }

    // In ProgramController.php// In ProgramController.php
    public function removeCourse($program_id, $course_id)
    {
        $program = Program::find($program_id);
        $course = Course::find($course_id);

        // Remove the course from the program
        $program->courses()->detach($course);

        return redirect()->route('program.show', $program_id)->with('success', 'Course removed successfully!');
    }
}
