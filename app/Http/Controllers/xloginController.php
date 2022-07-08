<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class loginController extends Controller
{
    //
    $this->validateLogin($request);
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    //

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string'
        ]);
    }

    protected function credentials(Request $request)
    {
        dd($request);

        $loginField = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->merge([
            $loginField => $request->input($this->username())
        ]);

        return $request->only($loginField, 'password');
    }
}