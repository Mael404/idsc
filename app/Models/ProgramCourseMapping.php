<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramCourseMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'course_id',
        'year_level_id',
        'semester_id',
        'price_per_unit',
        'effective_sy'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function miscFees()
    {
        return $this->hasMany(MiscFee::class);
    }
    public function getCombinationLabelAttribute()
    {
        return "{$this->program->name} - {$this->yearLevel->name} - {$this->semester->name} - SY: {$this->effective_sy}";
    }


    public function programCourseMapping()
    {
        return $this->belongsTo(\App\Models\ProgramCourseMapping::class, 'course_mapping_id', 'id');
    }
}
