<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'units', 'active']; // Add 'active'

    public function mappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }
}
