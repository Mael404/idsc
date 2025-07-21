<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'lrn_number',
        'last_name',
        'first_name',
        'middle_name',
        'birthday',
        'gender',
        'civil_status',
        'year_graduated',
        'checklist_clearance',
        'birthplace',
        'address',
        'mobile_number',
        'email_address',
        'secondary_school_name',
        'secondary_address',
        'region',
        'division_id',
        'last_school_attended',
        'classification',
        'father_name',
        'father_address',
        'father_occupation',
        'father_contact',
        'mother_name',
        'mother_address',
        'mother_occupation',
        'mother_contact',
        'guardian_name',
        'guardian_address',
        'guardian_relationship',
        'guardian_contact',
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }
    public function billing()
    {
        return $this->hasOne(ShsBilling::class, 'student_lrn', 'lrn_number')->latest('created_at');
    }
    public function enrollment()
    {
        return $this->hasOne(ShsEnrollment::class, 'student_id', 'student_id');
    }
}
