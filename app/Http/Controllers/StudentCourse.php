<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;

    protected $table = 'student_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'school_year',
        'semester',
        'status',
        'prelim',
        'midterm',
        'prefinal',
        'final',
        'final_grade',
        'grade_status',
        'force_prerequisite',
        'override_prereq',
    ];

    public $timestamps = true; 

    // Relationships
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // In App\Models\StudentCourse.php

    public static function getCurrentCourses($studentId, $schoolYear, $semester)
    {
        return self::with('course') // eager load the course data
            ->where('student_id', $studentId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->where('status', 'enrolled') // or whatever status means "currently taking"
            ->get();
    }
}
