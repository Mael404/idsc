<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        // Fetch all non-deleted scholarships
        $scholarships = Scholarship::all();

        // Fetch trashed scholarships for the modal
        $trashedScholarships = Scholarship::onlyTrashed()->get();

        return view('vp_admin.fees.scholarship', compact('scholarships', 'trashedScholarships'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric',
        ]);

        // Create a new scholarship
        Scholarship::create([
            'name' => $request->name,
            'discount' => $request->discount,
            'status' => 'active', // Optional default status
        ]);

        return redirect()->back()->with('success', 'Scholarship added successfully!');
    }

    public function toggleStatus($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->status = ($scholarship->status === 'active') ? 'inactive' : 'active';
        $scholarship->save();

        return redirect()->route('scholarships.index')->with('success', 'Scholarship status updated successfully.');
    }

    public function destroy($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->delete();  // Soft delete

        return redirect()->route('scholarships.index')->with('success', 'Scholarship moved to trash.');
    }

    public function restore($id)
    {
        $scholarship = Scholarship::onlyTrashed()->findOrFail($id);
        $scholarship->restore();

        return redirect()->route('scholarships.index')->with('success', 'Scholarship restored successfully.');
    }

    public function forceDelete($id)
    {
        $scholarship = Scholarship::onlyTrashed()->findOrFail($id);
        $scholarship->forceDelete();  // Permanently delete

        return redirect()->route('scholarships.index')->with('success', 'Scholarship permanently deleted.');
    }
}
