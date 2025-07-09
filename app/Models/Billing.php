<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'student_id',
        'school_year',
        'semester',
        'tuition_fee',
        'discount',
        'tuition_fee_discount',
        'misc_fee',
        'old_accounts',
        'total_assessment',
        'initial_payment',
        'balance_due',
        'is_full_payment',
        'prelims_due',
        'midterms_due',
        'prefinals_due',
        'finals_due',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
    public function details($id)
    {
        $billing = Billing::findOrFail($id);
        return response()->json($billing);
    }

    
}
