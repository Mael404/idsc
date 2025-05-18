<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;

class StudentSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $students = Admission::with('billing')
            ->where('student_id', 'like', "%$query%")
            ->orWhere('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->get()
            ->map(function ($student) {
                return [
                    'student_id' => $student->student_id,
                    'full_name' => $student->getFullNameAttribute(),
                    'balance_due' => optional($student->billing)->balance_due ?? 0,
                ];
            });

        return response()->json($students);
    }
}
