<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramCourseMiscFee extends Model
{
    use HasFactory;

    protected $fillable = ['program_course_mapping_id', 'misc_fee_id'];

    public function mapping()
    {
        return $this->belongsTo(ProgramCourseMapping::class, 'program_course_mapping_id');
    }

    public function miscFee()
    {
        return $this->belongsTo(MiscFee::class, 'misc_fee_id');
    }
}
