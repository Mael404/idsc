<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    // Show all billings

    public function index()
    {
        $billings = Billing::with('student')->get();
        return view('billing.index', compact('billings'));
    }

    // Search students for the payment modal
    public function searchStudents(Request $request)
    {
        $query = $request->input('query');
        
        $students = Admission::where('student_id', 'like', "%$query%")
            ->orWhere('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('birthdate', 'like', "%$query%")
            ->with(['billing' => function($q) {
                $q->select('student_id', 'balance_due');
            }])
            ->select('student_id', 'first_name', 'middle_name', 'last_name', 'birthdate')
            ->limit(10)
            ->get();
            
        // Format the data for the modal
        $formattedStudents = $students->map(function($student) {
            return [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'current_balance' => $student->billing ? $student->billing->balance_due : 0
            ];
        });
        
        return response()->json($formattedStudents);
    }

    // Get student's billing details
    public function getStudentBilling($studentId)
    {
        $student = Admission::with('billing')->find($studentId);
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        
        return response()->json([
            'student' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name
            ],
            'balance_due' => $student->billing ? $student->billing->balance_due : 0,
            'billing' => $student->billing
        ]);
    }


    // Show details of a specific billing as JSON


    // Show details of a specific billing as JSON
    public function details($id)
    {
        $billing = Billing::with('student')->findOrFail($id); // Include student details
        return response()->json($billing);
    }

    // Show the edit form for a specific billing
    public function edit($id)
    {
        $billing = Billing::findOrFail($id);
        return view('billing.edit', compact('billing'));
    }

    // Update a specific billing record
    public function update(Request $request, $id)
    {
        $request->validate([
            'school_year' => 'required|string',
            'semester' => 'required|string',
            'tuition_fee' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance_due' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        $billing = Billing::findOrFail($id);
        $billing->update($request->all());

        return redirect()->route('billings.index')->with('success', 'Billing updated successfully.');
    }

    // Delete a specific billing record
    public function destroy($id)
    {
        $billing = Billing::findOrFail($id);
        $billing->delete();

        return redirect()->route('billings.index')->with('success', 'Billing deleted successfully.');
    }
}
