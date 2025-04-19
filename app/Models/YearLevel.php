<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function mappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }
}
