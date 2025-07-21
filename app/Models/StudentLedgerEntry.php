<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLedgerEntry extends Model
{
    protected $table = 'student_ledger_entries';

    protected $fillable = [
        'student_id',
        'enrollment_id',
        'transaction_date',
        'description',
        'transaction_type',
        'amount_charged',
        'amount_paid',
        'reference_id',
    ];
}
