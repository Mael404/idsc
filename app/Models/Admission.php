<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

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
        'scholarship',
        'previous_school',
        'previous_school_address',
        'elementary_school',
        'elementary_address',
        'secondary_school',
        'secondary_address',
        'honors',
        'school_year',
        'semester',
    ];
}
