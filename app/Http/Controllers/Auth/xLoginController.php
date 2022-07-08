<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
	   return [
            'mail' => $request->get('email'),
            'password' => $request->get('password')
	    ];
    }
    protected function attemptLogin(Request $request)
    {
        dd('tes');
	if(Auth::attempt(['mail'=>$request->email,'password'=>$request->password])){
                $userStatus = Auth::User()->status;
                if($userStatus=='1') {
                    return redirect()->intended(url('/dashboard'));
                }else{    
		Auth::logout();
		$request->session()->flash('status', 'Task was successful!');
                    return redirect(url('login'))->withInput()->with('errorMsg','You are temporary blocked. please contact to admin');
                }
            }
            else {
               return redirect(url('login'))->withInput()->with('errorMsg','Incorrect username or password. Please try again.');
            }				
    }
}