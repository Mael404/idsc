<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * List all school years, including trashed (soft-deleted) ones.
     */
    public function index()
    {
        $schoolYears = SchoolYear::all();
        $trashedSchoolYears = SchoolYear::onlyTrashed()->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first(); //  get the active one

        return view('vp_admin.term_config.term-config', compact('schoolYears', 'trashedSchoolYears', 'activeSchoolYear'));
    }

    /**
     * Store a new school year.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'default_unit_price' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
            'semester' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'prelims_date' => 'nullable|date',
            'midterms_date' => 'nullable|date',
            'pre_finals_date' => 'nullable|date',
            'finals_date' => 'nullable|date',
        ]);


        // Check for duplicate name + semester combo
        $existing = SchoolYear::where('name', $request->name)
            ->where('semester', $request->semester)
            ->exists();

        if ($existing) {
            return back()->withErrors(['semester' => 'That School Year and Semester combo already exists.'])
                ->withInput();
        }

        // If marked active, deactivate others
        if ($request->input('is_active')) {
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
        }

        SchoolYear::create($request->all());

        return redirect()->route('school-years.index')->with('success', 'School Year added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|string',
            'default_unit_price' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'prelims_date' => 'nullable|date',
            'midterms_date' => 'nullable|date',
            'pre_finals_date' => 'nullable|date',
            'finals_date' => 'nullable|date',
        ]);


        // Check for duplicate name + semester combo, excluding current record
        $existing = SchoolYear::where('name', $request->name)
            ->where('semester', $request->semester)
            ->where('id', '!=', $id) //
            ->exists();

        if ($existing) {
            return back()->withErrors(['semester' => 'That School Year and Semester combo already exists.'])
                ->withInput();
        }

        // If marked active, deactivate others
        if ($request->input('is_active')) {
            SchoolYear::where('is_active', true)->where('id', '!=', $id)->update(['is_active' => false]);
        }

        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->update($request->all());

        return redirect()->route('school-years.index')->with('success', 'School Year updated successfully!');
    }


    /**
     * Soft-delete a school year.
     */
    public function destroy(string $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->delete();

        return redirect()->route('school-years.index')->with('success', 'School Year deleted successfully!');
    }

    /**
     * Permanently delete a school year.
     */
    public function forceDelete($id)
    {
        $schoolYear = SchoolYear::withTrashed()->findOrFail($id);
        $schoolYear->forceDelete();

        return back()->with('success', 'School Year permanently deleted.');
    }

    /**
     * Restore a soft-deleted school year.
     */
    public function restore($id)
    {
        $schoolYear = SchoolYear::withTrashed()->findOrFail($id);
        $schoolYear->restore();

        return redirect()->route('school-years.index')->with('success', 'School Year restored successfully!');
    }

    /**
     * Archive a school year (assuming archive() exists in model).
     */
    public function archive($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->archive();

        return back()->with('success', 'School Year archived.');
    }

    /**
     * Set a specific school year as active and deactivate others.
     */
    public function setActive($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);

        // If the selected school year is NOT active, activate it and deactivate all others
        if (!$schoolYear->is_active) {
            // Deactivate all school years first
            SchoolYear::where('is_active', true)->update(['is_active' => false]);

            // Activate the selected one
            $schoolYear->is_active = true;
            $schoolYear->save();
        } else {
            // If it's already active and we're toggling it off
            $schoolYear->is_active = false;
            $schoolYear->save();
        }

        return redirect()->route('school-years.index')->with('success', 'School Year status updated successfully!');
    }
}
