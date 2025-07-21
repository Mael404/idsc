<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function mappings()
    {
        return $this->hasMany(ProgramCourseMapping::class);
    }

    public function program()
    {
        return $this->belongsTo(\App\Models\Program::class, 'program_id', 'id');
    }
}
