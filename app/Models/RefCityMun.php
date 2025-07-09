<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCityMun extends Model
{
     use HasFactory;

    protected $table = 'refcitymun'; // Specify the correct table name
    public function province()
{
    return $this->belongsTo(RefProvince::class, 'provCode', 'provCode');
}

public function barangays()
{
    return $this->hasMany(RefBrgy::class, 'citymunCode', 'citymunCode');
}

}
