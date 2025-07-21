<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMiscFee extends Model
{
    protected $fillable = [
        'student_id',
        'billing_id',
        'school_year',
        'semester',
        'fee_name',
        'amount',
        'is_required'
    ];

    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}