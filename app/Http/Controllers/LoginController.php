<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    
    public function index()
    {
        if (Auth::user()) {
            // Authentication passed...
            
            return Redirect::to('dashboard');
        }
        return view('auth.login');
    }  
 
    public function register()
    {
        return view('auth.register');
    }

    public function generate()
    {
        return view('auth.generate');
    }
     
    // public function postLogin(Request $request)
    // {
    //     request()->validate([
    //     'password' => 'required|min:4'
    //     ]);
 
    //     // dd($request);

    //     $credentials = [
    //         'tenant_token' => $request->password,
    //         'password'  => $request->password
    //     ];
    //     dd(Auth::attempt($credentials));
    //     if (Auth::attempt($credentials)) {
    //         // Authentication passed...
    //         return Redirect::to('news');
    //     }
    //     return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
    // }
    public function login(Request $request)
    {
        $validator = request()->validate([
        'username' => 'required',
        'password' => 'required|min:4|confirmed'
        ]);
 
        // dd($request);

        $credentials = [
            'tenant_email' => $request->username,
            // 'password'  => strtoupper($request->password)
        ];
        // dd(Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return Redirect::to('news');
        }
        return Redirect::to("login")->withErrors($validator);
        // ->withSuccess('Oppes! You have entered invalid credentials');
    }

    public function postLogin(Request $request)
    {
        $validator = request()->validate([
            'username' => 'required|email',
            'password' => 'required|min:4'
            ]);
        if ($this->login_by_auth($request)) {
            $user = \App\User::where('pic_email1', $request->username)->orWhere('pic_email2', $request->username)->first();
            $this->guard('tenant')->login($user, true);
            $primary_user = \App\User::where('pic_email1', $request->username)->first();
            if (!empty($primary_user)) {
                Session::put('userLogin',['name' => $primary_user->pic_name1, 'email' => $primary_user->pic_email1] );
            }else{
                $secondary_user = \App\User::where('pic_email2', $request->username)->first();
                Session::put('userLogin',['name' => $secondary_user->pic_name2, 'email' => $secondary_user->pic_email2] );
            }
            return Redirect::to('news')->withErrors($validator);
        }else{
            // return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
            return Redirect::to("login")->withErrors($validator);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function login_by_auth($request)
    {
      $email = $request->username;
      // $password = $request->password;
      $primary_query = "SELECT * FROM bms_tenant WHERE pic_email1 = '$email'";
      $primary_check = collect(DB::select($primary_query))->first();
      // if (!empty($primary_check)) {
      //     $getPassword = (empty($primary_check->pic_password1) ? NULL: $primary_check->pic_password1);
      // }else{
      //     $secondary_query = "SELECT * FROM bms_tenant WHERE pic_email2 = '$email'";
      //     $secondary_check = collect(DB::select($secondary_query))->first();
      //     $getPassword = (empty($secondary_check->pic_password2) ? NULL: $secondary_check->pic_password2);
      // }
      // if (Hash::check($password, $getPassword)) {
      //     return true;

      // }else{
      //   return false;
      // }
      return true;
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            // $this->username() => 'required|string',
            $this->username() => 'required|string',
            $this->password() => 'required|string',
        ]);
    }

    public function username() {
        return 'username';
    }

    public function password()
    {
        return 'password';
    }

    protected function attemptLogin(Request $request)
    {
        // return $this->guard()->attempt(
        //     $this->credentials($request), $request->filled('remember')
        // );
        $credentials = array_merge($request->only($this->username(), 'password'),['status' => 1]);
        dd($credentials);
        $username = $credentials[$this->username()];
        // if (strpos($username, '@') !== false) {
        //   $username = $username.'@mmproperty.com';
        // }else{
        //   $username = $username;
        // }
        $check = $this->checkEmail($username);
        
        if( $check ) {
          $search = Adldap::search()->where('mail','=',$username)->first();
        } else {
          $search = Adldap::search()->where('uid','=',$username)->first();
        }
        
        
        if (empty($search->uid[0])) {
          $username = '';
        }else{
          $username = $search->uid[0];
        }
        
        $password = $credentials['password'];
        
        $user_status = \App\User::where([['username', $username],['status',1]])->first();
        
        if (!$user_status) {
          return false;
        }else{
          if(Adldap::auth()->attempt('uid='.$username.',ou=Employee,dc=ad,dc=mmproperty,dc=com', $password, $bindAsUser = true)) {
              // the user exists in the LDAP server, with the provided password
              // if (trim($user_status->password) == '') {
              //   \App\User::where('id', $user_status->id)
              //   ->update(['password' => Hash::make(strtoupper($password))]);
              // }

              $user = \App\User::where('username', $username) -> first();
              if (!$user) {
                  // the user doesn't exist in the local database, so we have to create one

                  $user = new \App\User();
                  $user->username = $username;
                  $user->password = '';

                  // you can skip this if there are no extra attributes to read from the LDAP server
                  // or you can move it below this if(!$user) block if you want to keep the user always
                  // in sync with the LDAP server
                  $sync_attrs = $this->retrieveSyncAttributes($username);
                  foreach ($sync_attrs as $field => $value) {
                      $user->$field = $value !== null ? $value : '';
                  }
              }
              // by logging the user we create the session so there is no need to login again (in the configured time)
              $this->guard()->login($user, true);
              return true;
          }
        }
        // the user doesn't exist in the LDAP server or the password is wrong
        // log error
    }


    public function putGenerate(Request $request)
    {
        $tenant = User::all();
        foreach ($tenant as $tenant) {
            // if (!empty($tenant->tenant_token)) {
            //     User::where('tenant_id', $tenant->tenant_id)
            //     ->update(['password' => Hash::make($tenant->tenant_token)]);
            // }else{
                $random = Str::random(5);
                User::where('tenant_id', $tenant->tenant_id)
                ->update(['tenant_token' => strtoupper($random), 'password' => Hash::make(strtoupper($random))]);
            // }
        }
        
        return Redirect::to("generate");
    }
 
    public function postRegister(Request $request)
    {  
        request()->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        $check = $this->create($data);
       
        return Redirect::to("news")->withSuccess('Great! You have Successfully loggedin');
    }
     
    public function dashboard()
    {
 
      if(Auth::check()){
        return view('news');
      }
       return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
     
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}