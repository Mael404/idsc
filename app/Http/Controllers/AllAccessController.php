<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class AllAccessController extends Controller
{
    //
    public function dashboard()
    {
        return view('all.all_db');
    }

    public function queueing()
    {

        return view('all.queueing');
    }

    
}
