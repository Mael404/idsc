<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'year_level_id',
        'semester_id',
        'effective_sy',
    ];

    // Relationship with ProgramCourseMapping
    public function courseMappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }
}
