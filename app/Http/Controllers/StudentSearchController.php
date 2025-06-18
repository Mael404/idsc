<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use Illuminate\Support\Facades\DB;

class StudentSearchController extends Controller
{
  public function search(Request $request)
{
    $query = $request->get('query', '');

    $activeSY = DB::table('school_years')
        ->where('is_active', 1)
        ->whereNull('deleted_at')
        ->first();

    if (!$activeSY) {
        return response()->json([]);
    }

    // Get matching students (no filtering by billing yet)
    $students = Admission::where(function ($q) use ($query) {
            $q->where('student_id', 'like', "%$query%")
              ->orWhere('first_name', 'like', "%$query%")
              ->orWhere('last_name', 'like', "%$query%");
        })
        ->with(['billing' => function ($q) use ($activeSY) {
            $q->where('school_year', $activeSY->name)
              ->where('semester', $activeSY->semester);
        }])
        ->get()
        ->map(function ($student) {
            return [
                'student_id'  => $student->student_id,
                'full_name'   => $student->getFullNameAttribute(),
                'balance_due' => optional($student->billing)->balance_due ?? 0,
                'school_year' => optional($student->billing)->school_year ?? '',
                'semester'    => optional($student->billing)->semester ?? '',
            ];
        });

    return response()->json($students);
}



}