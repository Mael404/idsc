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
    ];

    public $timestamps = true; // Only if you added created_at and updated_at fields

    // Relationships
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
