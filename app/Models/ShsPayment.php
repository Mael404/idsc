<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShsPayment extends Model
{
    protected $table = 'shs_payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'enrollment_id',
        'payee_type',
        'total_amount_due',
        'initial_payment',
        'payment_installment_1',
        'payment_installment_2',
        'payment_installment_3',
        'payment_installment_4',
        'balance_due',
        'payment_date',
        'processed_by',
        'or_number',
        'school_year',
        'is_void',
        'amount',
        'payment_method',
        'ref_number',
        'status',
    ];

    public function enrollment()
    {
        return $this->belongsTo(ShsEnrollment::class, 'enrollment_id');
    }
    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            ShsEnrollment::class,
            'enrollment_id',
            'student_id',
            'enrollment_id',
            'student_id'
        );
    }
}
