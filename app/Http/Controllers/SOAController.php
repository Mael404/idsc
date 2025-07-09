<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Scholarship;

class SOAController extends Controller
{
    /**
     * Display the list of admissions with details.
     */
    public function index()
    {
        // Fetch admissions and scholarships
        $admissions = Admission::with(['programCourseMapping.program'])->orderBy('created_at')->get();
        $scholarships = Scholarship::all();

        return view('admissions.index', compact('admissions', 'scholarships'));
    }
}
