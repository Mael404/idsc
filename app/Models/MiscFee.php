<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiscFee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount', 'program_course_mapping_id'];

    public function programCourseMapping()
    {
        return $this->belongsTo(ProgramCourseMapping::class);
    }
}
