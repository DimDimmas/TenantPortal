<?php
  
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
  
  
class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('dashboard');
        }
        // return redirect('/');
        return view('auth.login');
    }

    public function register()
    {

      return view('auth.register');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        return redirect('home');
    }


    public function authenticate(Request $request)
    {
        // $request->validate([
        //     'tenant_token' => 'required|string|max:5',
        // ]);
        
        $credentials = $request->only('password');
        // $tenant_token = $credentials['tenant_token'];
        // $user = User::where('tenant_token', $tenant_token) -> first();
        // $user['password'] = $credentials['tenant_token'];
        dd(Auth::attempt($credentials));
        if (Auth::attempt($user)) { 
            return redirect()->intended('home');
        }

        return redirect('login')->with('error', 'Oppes! You have entered invalid credentials');
    }

    public function logout() {
      Auth::logout();

      return redirect('login');
    }

    public function username(){ return 'tenant_token'; }

    public function home()
    {
      return view('home');
    }
  
  
}