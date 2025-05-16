<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\ProgramCourseMapping;
use App\Models\SchoolYear;
use App\Models\StudentCourse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::latest()->get();

        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel', 'semester'])
            ->get()
            ->groupBy(function ($item) {
                return $item->program_id . '-' . $item->year_level_id . '-' . $item->semester_id . '-' . $item->effective_sy;
            })
            ->map(function ($group) {
                return $group->first(); // pick one representative from the group
            })
            ->sortBy(fn($mapping) => $mapping->program->name ?? '');

        $allCourses = \App\Models\Course::orderBy('id')->get();


        return view('registrar.enrollment.enrollment', compact('admissions', 'courseMappings', 'allCourses')); // ✅ Add here
    }


    public function store(Request $request)
    {
        Log::debug('Form submission data:', $request->all());

        if ($request->has('course_ids')) {
            Log::debug('Selected course IDs:', ['courses' => $request->course_ids]);
        }
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'school_year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'course_mapping_id' => 'nullable|exists:program_course_mappings,id',
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        // ✅ Generate unique student ID (format: YY-XXX)
        do {
            $studentId = date('y') . '-' . rand(100, 999);
        } while (Admission::where('student_id', $studentId)->exists());

        $admission = Admission::create([
            'student_id' => $studentId,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'zip_code' => $request->zip_code,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'father_last_name' => $request->father_last_name,
            'father_first_name' => $request->father_first_name,
            'father_middle_name' => $request->father_middle_name,
            'father_contact' => $request->father_contact,
            'father_profession' => $request->father_profession,
            'father_industry' => $request->father_industry,
            'mother_last_name' => $request->mother_last_name,
            'mother_first_name' => $request->mother_first_name,
            'mother_middle_name' => $request->mother_middle_name,
            'mother_contact' => $request->mother_contact,
            'mother_profession' => $request->mother_profession,
            'mother_industry' => $request->mother_industry,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'birthplace' => $request->birthplace,
            'citizenship' => $request->citizenship,
            'religion' => $request->religion,
            'civil_status' => $request->civil_status,
            'course_mapping_id' => $request->course_mapping_id,
            'major' => $request->major,
            'admission_status' => $request->admission_status,
            'student_no' => $request->student_no,
            'admission_year' => $request->admission_year,
            'scholarship' => $request->scholarship,
            'previous_school' => $request->previous_school,
            'previous_school_address' => $request->previous_school_address,
            'elementary_school' => $request->elementary_school,
            'elementary_address' => $request->elementary_address,
            'secondary_school' => $request->secondary_school,
            'secondary_address' => $request->secondary_address,
            'honors' => $request->honors,
            'school_year' => $activeSchoolYear ? $activeSchoolYear->name : $request->school_year,
            'semester' => $activeSchoolYear ? $activeSchoolYear->semester : $request->semester,
        ]);

        // ✅ Detect if student is irregular
        $isIrregular = in_array($request->admission_status, ['transferee', 'returnee']);

        if ($isIrregular && $request->has('course_ids')) {
            foreach ($request->course_ids as $courseId) {
                try {
                    StudentCourse::create([
                        'student_id' => $admission->student_id,
                        'course_id' => $courseId,
                        'school_year' => $admission->school_year,
                        'semester' => $admission->semester,
                        'status' => 'enrolled',
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error inserting student course: ' . $e->getMessage());
                }
            }
        } else {
            // Existing logic for regulars
            $mapping = ProgramCourseMapping::find($request->course_mapping_id);

            $relatedMappings = ProgramCourseMapping::where('program_id', $mapping->program_id)
                ->where('year_level_id', $mapping->year_level_id)
                ->where('semester_id', $mapping->semester_id)
                ->where(function ($query) use ($mapping) {
                    if ($mapping->effective_sy === null) {
                        $query->whereNull('effective_sy');
                    } else {
                        $query->where('effective_sy', $mapping->effective_sy);
                    }
                })
                ->get();

            foreach ($relatedMappings as $map) {
                StudentCourse::create([
                    'student_id' => $admission->student_id,
                    'course_id' => $map->course_id,
                    'school_year' => $admission->school_year,
                    'semester' => $admission->semester,
                    'status' => 'enrolled', // or any default
                ]);
            }
        }

        return redirect()->route('admissions.index')->with('success', 'Admission created successfully!');
    }

    public function create()
    {
        $courseMappings = ProgramCourseMapping::with(['program', 'yearLevel'])
            ->get()
            ->sortBy(function ($mapping) {
                return $mapping->program->name ?? '';
            });

        $allCourses = \App\Models\Course::orderBy('code')->get(); // 👈 Add this line

        return view('registrar.enrollment.enrollment', compact('courseMappings', 'allCourses')); // 👈 Include it here
    }
}
