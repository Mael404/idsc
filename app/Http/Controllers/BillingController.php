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
            ->with(['billing' => function ($q) {
                $q->select('student_id', 'balance_due');
            }])
            ->select('student_id', 'first_name', 'middle_name', 'last_name', 'birthdate')
            ->limit(10)
            ->get();

        // Format the data for the modal
        $formattedStudents = $students->map(function ($student) {
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


    public function getRevenueTrends()
    {
        // Aggregate total_assessment grouped by both school_year and semester
        $revenueTrends = Billing::selectRaw('school_year, semester, SUM(total_assessment) as total_revenue')
            ->groupBy('school_year', 'semester')
            ->orderBy('school_year', 'asc')
            ->orderBy('semester', 'asc')
            ->get();

        return response()->json($revenueTrends);
    }

    public function getBalanceDue()
    {
        // Aggregate balance_due grouped by semester or any relevant category
        $balanceDueData = Billing::selectRaw('semester, SUM(balance_due) as total_balance_due')
            ->groupBy('semester')
            ->orderBy('semester', 'asc')
            ->get();

        return response()->json($balanceDueData);
    }

    // Add this method to your BillingController
    public function getDailySales()
    {
        // Get active school year and semester
        $activeSemester = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSemester) {
            return response()->json([
                'error' => 'No active semester found'
            ], 404);
        }

        // Calculate totals from payments table
        $data = [
            'total_sales' => \App\Models\Payment::where('school_year', $activeSemester->name)
                ->where('semester', $activeSemester->semester)
                ->sum('amount'),
            'regular_payments' => \App\Models\Payment::where('school_year', $activeSemester->name)
                ->where('semester', $activeSemester->semester)
                ->whereNull('payment_type')
                ->sum('amount'),
            'other_payments' => \App\Models\Payment::where('school_year', $activeSemester->name)
                ->where('semester', $activeSemester->semester)
                ->where('payment_type', 'others')
                ->sum('amount'),
            'semester_info' => $activeSemester->name . ' - ' . $activeSemester->semester
        ];

        return response()->json($data);
    }
}
