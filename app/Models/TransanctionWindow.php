<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransanctionWindow extends Model
{
    //
    protected $table = 'transaction_windows';
    protected $fillable = [
        'transaction_id',
        'name',
        'status'
    ];
}
