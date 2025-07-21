<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherPayment extends Model
{
    protected $fillable = [
        'student_id',
        'lrn_number',
        'amount',
        'remarks',
        'payment_date',
        'or_number',
        'school_year',
        'semester',
        'payment_type',
        'is_void',
        'voided_at',
        'grading_period',
        'remaining_balance',
        'processed_by',
        'payment_method',
    ];

    public function college()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
    public function shs()
    {
        return $this->belongsTo(Student::class, 'lrn_number', 'lrn_number');
    }
}
