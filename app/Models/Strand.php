<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strand extends Model
{
    protected $table = 'strands'; 
    protected $primaryKey = 'strand_id';
    protected $fillable = [
        'strand_name',
        
    ];
}
