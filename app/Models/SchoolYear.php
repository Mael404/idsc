<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add this line

class SchoolYear extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes trait

    protected $fillable = [
        'name',
        'default_unit_price',
        'semester',
        'is_active',
        'start_date',
        'end_date',
        'prelims_date',
        'midterms_date',
        'pre_finals_date',
        'finals_date',
    ];
}
