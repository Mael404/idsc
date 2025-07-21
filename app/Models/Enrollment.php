<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    // Table name (optional if your table is named 'enrollments')
    protected $table = 'enrollments';

    // Fillable fields for mass assignment
    protected $fillable = [
        'student_id',
        'school_year',
        'semester',
        'course_mapping_id',
        'status',
        'enrollment_type',
        'scholarship_id',
        'enrollment_date',
        'enrollment_id',
    ];

    // If your primary key is not 'id' or not incrementing, define here (not necessary if default)
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
    // Define relationship to Admission (student)
    public function admission()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }

    // Define relationship to ProgramCourseMapping
    public function courseMapping()
    {
        return $this->belongsTo(ProgramCourseMapping::class, 'course_mapping_id', 'id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'student_id', 'student_id');
    }

    public function currentBilling()
    {
        return Billing::where('student_id', $this->student_id)
            ->where('school_year', $this->school_year)
            ->where('semester', $this->semester)
            ->first();
    }
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }
    public function programCourseMapping()
    {
        return $this->belongsTo(ProgramCourseMapping::class, 'course_mapping_id', 'id');
    }
}
