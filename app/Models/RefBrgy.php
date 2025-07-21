<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefBrgy extends Model
{
      use HasFactory;

    protected $table = 'refbrgy'; // Specify the correct table name
 public function city()
{
    return $this->belongsTo(RefCityMun::class, 'citymunCode', 'citymunCode');
}

}
