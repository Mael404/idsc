<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    //
    protected $table = 'queue_list';
    protected $fillable = [
        'name',
        'transaction_id',
        'window_id',
        'queue_no',
        'purpose',
        'status',
        'status_trigger',
        'date_created',
        'created_timestamp'
    ];
    public $timestamps = false;
    public function window()
    {
        return $this->belongsTo(TransanctionWindow::class, 'window_id');
    }
}
