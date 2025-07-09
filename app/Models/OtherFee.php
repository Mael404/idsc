<?php

// app/Models/OtherFee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherFee extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'amount', 'status'];
}
