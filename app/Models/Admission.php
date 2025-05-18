<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;
    protected $primaryKey = 'student_id'; // ✅ use student_id instead of id
    public $incrementing = false;         // ✅ if student_id is not auto-incrementing
    protected $keyType = 'string';        // ✅ if student_id is a string (like '25-624')
    protected $fillable = [
        'student_id',
        'last_name',
        'first_name',
        'middle_name',
        'address_line1',
        'address_line2',
        'zip_code',
        'contact_number',
        'email',
        'father_last_name',
        'father_first_name',
        'father_middle_name',
        'father_contact',
        'father_profession',
        'father_industry',
        'mother_last_name',
        'mother_first_name',
        'mother_middle_name',
        'mother_contact',
        'mother_profession',
        'mother_industry',
        'gender',
        'birthdate',
        'birthplace',
        'citizenship',
        'religion',
        'civil_status',
        'course_mapping_id',
        'major',
        'admission_status',
        'student_no',
        'admission_year',
        'scholarship_id',
        'previous_school',
        'previous_school_address',
        'elementary_school',
        'elementary_address',
        'secondary_school',
        'secondary_address',
        'honors',
        'school_year',
        'semester',
        'status',
    ];

    public function courseMapping()
    {
        return $this->belongsTo(\App\Models\ProgramCourseMapping::class, 'course_mapping_id');
    }

    // In App\Models\Admission.php
    public function billing()
    {
        return $this->hasOne(Billing::class, 'student_id', 'student_id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }
}
