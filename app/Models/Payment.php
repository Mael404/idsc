<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
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
        'ref_number',
        'status',
    ];


    // Relationships
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
}
