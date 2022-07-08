<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Response;


class AuthController extends Controller
{

    public function checkSession()
    {
        return Response::json(['guest' => Auth::guest()]);
    }
}