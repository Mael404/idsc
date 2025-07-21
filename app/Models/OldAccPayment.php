<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldAccPayment extends Model
{
    //
    protected $table = 'old_acc_payments';
    protected $fillable = [
        'student_id',
        'or_number',
        'amount',
        'particulars',
        'payment_date',
        'is_void',
        'processed_by',
        'status'
    ];
    public function student()
    {
        return $this->belongsTo(OldAccount::class, 'student_id');
    }
}
