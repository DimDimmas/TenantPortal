<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Validator,Redirect,Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Session;
use Illuminate\Http\Request;
use App\tenantModel;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    // /**
    //  * Where to redirect users after resetting their password.
    //  *
    //  * @var string
    //  */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function index($user)
    {
        $a = tenantModel::where('bms_tenant.pic_email1', Session::get('userLogin')['email'])->orWhere('bms_tenant.pic_email2', Session::get('userLogin')['email']);
        
        $tenant = $a
                    ->leftjoin('bms_tenant_company', 'bms_tenant_company.tenant_code', '=', 'bms_tenant.tenant_code')
                    ->select('bms_tenant.*', 'bms_tenant_company.*')
                    ->first();
        return view('auth.passwords.reset', [
            'tenant' => $tenant
        ]);
    }

    public function submitNewPassword(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required|confirmed|min:4'
        ]);

        
            $primary_check = tenantModel::where('pic_email1',Session::get('userLogin')['email'])->first();
            if (!empty($primary_check)) {
                tenantModel::where('pic_email1',Session::get('userLogin')['email'])->update(['pic_password1' => Hash::make($request->password)]);
                // return Redirect::to('news');
            }else{
                $secondary_check = tenantModel::where('pic_email2',Session::get('userLogin')['email'])->first();
                tenantModel::where('pic_email2',Session::get('userLogin')['email'])->update(['pic_password2' => Hash::make($request->password)]);
                return Redirect::to('news');
            }
            return Redirect::to('news')->withErrors($validator);
    }
}