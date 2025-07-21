<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefProvince extends Model
{

     use HasFactory;

    protected $table = 'refprovince'; // Specify the correct table name
    public function region()
{
    return $this->belongsTo(RefRegion::class, 'regCode', 'regCode');
}

public function cities()
{
    return $this->hasMany(RefCityMun::class, 'provCode', 'provCode');
}

}
