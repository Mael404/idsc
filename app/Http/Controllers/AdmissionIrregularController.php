<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdmissionIrregularController extends Controller
{
      public function store(Request $request)
    {
        // Handle the form submission for irregular admissions here
        // You can access form data using $request->input('fieldname')
        
        // Example:
        // $data = $request->validate([
        //     'field1' => 'required',
        //     'field2' => 'required',
        // ]);
        
        // Process the data...
        
        // return redirect()->back()->with('success', 'Admission submitted successfully!');
    }

    public function calculateIrregularTuition(Request $request)
{
    $courseIds = $request->input('course_ids', []); // Expects an array of course IDs
    $totalUnits = 0;
    $unitPrice = 0;
    $tuitionFee = 0;

    if (!empty($courseIds)) {
        $totalUnits = \App\Models\Course::whereIn('id', $courseIds)->sum('units');
    }

    $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

    if ($activeSchoolYear) {
        $unitPrice = $activeSchoolYear->default_unit_price ?? 0;
        $tuitionFee = $totalUnits * $unitPrice;
    }

    return response()->json([
        'total_units' => $totalUnits,
        'tuition_fee' => $tuitionFee,
        'unit_price' => $unitPrice
    ]);
}

}
