<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'citizenship',
        'religion',
        'civil_status',
        'address',
        'status',
        'student_number',
        'primary_school',
        'primary_address',
        'secondary_school',
        'secondary_address',
        'father_first_name',
        'father_middle_name',
        'father_last_name',
        'father_occupation',
        'father_contact_number',
        'father_address',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'mother_occupation',
        'mother_contact_number',
        'mother_address',
        'so_number',
        'date_of_graduation',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_graduation' => 'date',
    ];
}
