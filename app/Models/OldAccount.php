<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldAccount extends Model
{
    //
    protected $table = 'old_accounts';
    protected $fillable = ['name', 'course_strand', 'year_graduated', 'balance'];

    public function payments()
    {
        return $this->hasMany(OldAccPayment::class, 'student_id');
    }
}
