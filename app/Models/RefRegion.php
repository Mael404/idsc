<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefRegion extends Model
{
    use HasFactory;

    protected $table = 'refregion'; // Specify the correct table name

    /**
     * Define a relationship to the RefProvince model.
     * 
     * A region has many provinces.
     */
    public function provinces()
    {
        return $this->hasMany(RefProvince::class, 'regCode', 'regCode');
    }
}
