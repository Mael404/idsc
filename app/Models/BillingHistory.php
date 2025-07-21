<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'user_id',
        'action',
        'description',
        'old_amount',
        'new_amount',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array',
        'old_amount' => 'decimal:2',
        'new_amount' => 'decimal:2',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}