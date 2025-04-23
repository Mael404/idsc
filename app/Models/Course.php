<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'units', 'active', 'prerequisite_id']; // Add 'prerequisite_id'

    /**
     * Get the course's prerequisite.
     */
    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    /**
     * Get all the courses that have this course as a prerequisite.
     */
    public function prerequisitesForOthers()
    {
        return $this->hasMany(Course::class, 'prerequisite_id');
    }

    public function mappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }
}
