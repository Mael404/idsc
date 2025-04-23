<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemestersController extends Controller
{
    // Display all semesters
    public function index()
    {
        // Fetch all semesters
        $semesters = Semester::all();

        // Pass the semesters data to the view
        return view('vp_academic.course_management.semester', compact('semesters'));
    }

    // Store a new semester
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Semester::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Semester added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Semester updated successfully.');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

        return redirect()->back()->with('success', 'Semester deleted successfully.');
    }
}
