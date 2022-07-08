<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

//use AppConnection;

class underconstructionController extends Controller
{  
    public function index() {
        return view('welcome');  
    }
}