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
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Admission::class, 'student_id', 'student_id');
    }
}
