<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformPayment extends Model
{
    //
    protected $table = 'uniform_payments';
    protected $fillable = [
        'student_id',
        'school_year',
        'lrn_number',
        'semester',
        'grading_period',
        'processed_by',
        'amount',
        'payment_date',
        'created_at',
        'updated_at',
        'trans_no',
        'is_void',
        'status',
        'voided_at',
        'voided_by'
    ];
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
    public function college()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
    public function shs()
    {
        return $this->belongsTo(Student::class, 'lrn_number', 'lrn_number');
    }
}
