<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'effective_school_year'];

    public function mappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }
    
}
