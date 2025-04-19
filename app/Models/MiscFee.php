<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiscFee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount'];

    public function mappings()
    {
        return $this->belongsToMany(ProgramCourseMapping::class, 'program_course_misc_fees');
    }
}
