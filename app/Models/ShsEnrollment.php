<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShsEnrollment extends Model
{
    protected $table = 'shs_enrollments';
    protected $primaryKey = 'enrollment_id';

    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'student_id',
        'school_years',
        'semester',
        'grade_level',
        'strand',
        'track',
        'section',
        'status',
        'classification',
        'esc_number',
        'voucher_recipient_checklist',
        'type_of_payee',
        'school_year',
        'school_year_id',
        'semester_id',
        'strand_id',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(ShsPayment::class);
    }

    // public function enrollment()
    // {
    //     return $this->belongsTo(ShsEnrollment::class, 'student_id');
    // }

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id', 'strand_id');
    }
    public function gradeLevel()
    {
        return $this->belongsTo(YearLevel::class, 'school_year_id');
    }
    public function getCombinationLabelAttribute()
    {
        $strand = $this->strand ?? 'N/A';
        $schoolYear = $this->school_years ?? 'N/A';

        return "$strand - $this->grade_level - SY: $schoolYear";
    }
}
