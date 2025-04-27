<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $allCourses = Course::orderBy('name', 'asc')->get(); // Alphabetical order
        return view('vp_academic.course_management.courses', compact('courses', 'allCourses'));
    }

    public function create()
    {
        $allCourses = Course::orderBy('name', 'asc')->get(); // Alphabetical order
        return view('vp_academic.course_management.courses', compact('allCourses'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'code' => 'required|string|unique:courses,code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'units' => 'required|numeric',
            'lecture_hours' => 'required|numeric',
            'lab_hours' => 'required|numeric',
            'prerequisite_id' => 'nullable|exists:courses,id',
        ]);

        // Create the course
        $course = Course::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'units' => $validated['units'],
            'lecture_hours' => $validated['lecture_hours'],
            'lab_hours' => $validated['lab_hours'],
            'prerequisite_id' => $validated['prerequisite_id'] ?? null,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:courses,code,' . $id,
            'name' => 'required|string',
            'description' => 'nullable|string',
            'units' => 'required|numeric',
            'lecture_hours' => 'required|numeric',
            'lab_hours' => 'required|numeric',
            'prerequisite_id' => 'nullable|exists:courses,id',
        ]);

        $course = Course::findOrFail($id);
        $course->update($validated);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }

    public function toggleActive($id)
    {
        $course = Course::findOrFail($id);
        $course->active = !$course->active;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course status updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
