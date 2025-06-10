<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'units',
        'lecture_hours',
        'lab_hours',
        'active',
        'prerequisite_id'
    ];

    /**
     * Get the course's prerequisite.
     */
    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'course_id', 'prerequisite_id');
    }

    public function isPrerequisiteFor()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'prerequisite_id', 'course_id');
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

    // In App\Models\Course.php
    public function hasPrerequisites()
    {
        return $this->prerequisites()->exists();
    }
}
