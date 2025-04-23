<?php

namespace App\Http\Controllers;

use App\Models\YearLevel;
use Illuminate\Http\Request;

class YearLevelController extends Controller
{
    public function index()
    {
        // Fetch all year levels
        $year_levels = YearLevel::all();
    
        // Pass the year_levels data to the view
        return view('vp_academic.course_management.year', compact('year_levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        YearLevel::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Year Level added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $yearLevel = YearLevel::findOrFail($id);
        $yearLevel->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Year Level updated successfully.');
    }

    public function destroy($id)
    {
        $yearLevel = YearLevel::findOrFail($id);
        $yearLevel->delete();

        return redirect()->back()->with('success', 'Year Level deleted successfully.');
    }
}
