<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function store(Request $request)
    {
        // Validate sthe incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric',
        ]);

        // Create the new scholarship record
        $scholarship = Scholarship::create([
            'name' => $request->name,
            'discount' => $request->discount,
            'status' => 'active', // You can modify this logic as needed
        ]);

        return redirect()->back()->with('success', 'Scholarship added successfully!');
    }
    public function index()
    {
        $scholarships = Scholarship::withTrashed()->get();  // Include soft deleted records
        return view('scholarships.index', compact('scholarships'));
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

    // In ScholarshipController.php

    public function trashed()
    {
        // Fetch trashed scholarships (soft deleted)
        $trashedScholarships = Scholarship::onlyTrashed()->get();  // Fetch trashed scholarships

        // Return the correct view with the trashed scholarships
        return view('vp_admin.fees.scholarship', compact('trashedScholarships'));
    }



    public function restore($id)
    {
        $scholarship = Scholarship::onlyTrashed()->findOrFail($id);
        $scholarship->restore();

        return redirect()->route('scholarships.trashed')->with('success', 'Scholarship restored successfully.');
    }

    public function forceDelete($id)
    {
        $scholarship = Scholarship::onlyTrashed()->findOrFail($id);
        $scholarship->forceDelete();  // Permanently delete the scholarship

        return redirect()->route('scholarships.trashed')->with('success', 'Scholarship permanently deleted.');
    }
}
