<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShsBilling extends Model
{
    protected $table = 'shs_billings';
    protected $fillable = [
        'student_lrn',
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
        return $this->belongsTo(Student::class, 'student_lrn', 'lrn_number');
    }

    public function details($id)
    {
        $billing = ShsBilling::findOrFail($id);
        return response()->json($billing);
    }
}
