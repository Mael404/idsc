<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MiscFee;
use App\Models\ProgramCourseMapping;

class MiscFeeController extends Controller
{
    // Store a new misc fee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_course_mapping_id' => 'required|exists:program_course_mappings,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        MiscFee::create($validated);

        return redirect()->back()->with('success', 'Miscellaneous fee added successfully.');
    }

    // Delete a misc fee
    public function destroy($id)
    {
        $fee = MiscFee::findOrFail($id);
        $fee->delete();

        return redirect()->back()->with('success', 'Miscellaneous fee deleted.');
    }

    public function getList($mappingId)
    {
        $mappingIds = ProgramCourseMapping::where('id', $mappingId)->pluck('id')->toArray();
        $fees = MiscFee::whereIn('program_course_mapping_id', $mappingIds)->get();
        $total = $fees->sum('amount');

        return view('partials.misc-fee-list', compact('fees', 'total'));
    }
    public function storeBulk(Request $request)
    {
        $request->validate([
            'program_course_mapping_id' => 'required|integer|exists:program_course_mappings,id',
            'fees_json' => 'required|json',
        ]);

        $fees = json_decode($request->fees_json, true);

        foreach ($fees as $fee) {
            MiscFee::create([
                'program_course_mapping_id' => $request->program_course_mapping_id,
                'name' => $fee['name'],
                'amount' => $fee['amount'],
            ]);
        }

        return back()->with('success', 'Miscellaneous fees added successfully.');
    }
}
