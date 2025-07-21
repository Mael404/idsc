<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfPee extends Model
{
    protected $table = 'types_of_pee';

    protected $fillable = [
        'name',
        'tuition'
    ];
}
