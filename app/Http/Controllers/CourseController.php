<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CourseController extends Controller
{
    // CourseController.php

    public function index()
    {
        // Fetch all courses and pass to the view
        $courses = Course::all();
        $allCourses = Course::all(); // Get all courses for the prerequisite dropdown
        return view('vp_academic.course_management.courses', compact('courses', 'allCourses'));
    }
    
    public function create()
    {
        $allCourses = Course::all();  // Get all courses for prerequisite dropdown
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
            'prerequisite_id' => 'nullable|exists:courses,id', // Ensure prerequisite exists
        ]);
    
        // Create the course
        $course = Course::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'units' => $validated['units'],
        ]);
    
        // If a prerequisite is selected, save it in the course_prerequisite table
        if ($validated['prerequisite_id']) {
            DB::table('course_prerequisite')->insert([
                'course_id' => $course->id,
                'prerequisite_id' => $validated['prerequisite_id']
            ]);
        }
    
        return redirect()->route('courses.index')->with('success', 'Course added successfully!');
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
