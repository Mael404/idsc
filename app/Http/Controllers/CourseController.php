<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // CourseController.php

    public function index()
    {
        // Fetch all courses
        $courses = Course::all();

        return view('vp_academic.course_management.courses', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:courses,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'units' => 'required|integer|min:0',
        ]);

        Course::create($request->only('code', 'name', 'description', 'units'));

        return redirect()->back()->with('success', 'Course added successfully!');
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }

    public function toggleActive($id)
    {
        $course = Course::findOrFail($id);
        $course->active = !$course->active; // Toggle the 'active' status
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course status updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
