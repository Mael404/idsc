<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('vp_academic.course_management.programs', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:programs',
            'effective_school_year' => 'required',
        ]);

        Program::create($request->all());
        return redirect()->back()->with('success', 'Program added successfully.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->back()->with('success', 'Program deleted successfully.');
    }

    public function toggleActive($id)
    {
        $program = Program::findOrFail($id);
        $program->active = !$program->active;
        $program->save();

        return back()->with('status', 'Program status updated!');
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'effective_school_year' => 'required|string|max:9', // Assuming format like "2024-2025"
        ]);

        // Update the program
        $program->name = $request->input('name');
        $program->code = $request->input('code');
        $program->effective_school_year = $request->input('effective_school_year');
        
        
        $program->save();

        return redirect()->route('programs.index')->with('success', 'Program updated successfully');
    }
}
