<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmissionController extends Controller
{
    public function index()
    {
        // Optional: Show all admissions or the form itself
        return view('registrar.enrollment.enrollment');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'email' => 'required|email',
            'contact_number' => 'required|string',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
    
        ]);

       
        return redirect()->back()->with('success', 'Admission form submitted successfully!');
    }
}
