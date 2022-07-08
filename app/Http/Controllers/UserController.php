<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
	
	//$role = Role::findById(1);
	//auth()->user()->assignRole('1');
	//$role->givePermissionTo(1,2,3,4,5);
	//auth()->user()->givePermissionTo('1');
	$user = User::all();
	
	return view ('users.index')->with('name',$user);    
	//return view ('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

	$user->status = request('status');
	$user->save();  
	Session::flash('success', 'Update is successfully submit');
	return back();
	
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
	return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
		$role = Role::orderBy('name', 'ASC')->get();
        return view('users.edit', compact('user','role'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
			
        $input = $request->all();
        
        $user = User::find($id);
        
	$user->update([
            $user->profile_id = request('profile_id'),
            $user->status = (!request()->has('status') == '1' ? '0' : '1'),
            $user->assignRole($request->input('roles'))
            ]);
	$user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function synchLdap()
    {
	Artisan::call('ldap:import', ['provider' => 'ldap', '--no-interaction']);
	return "LDAP data is Synch";
    }

    public function changeStatus(Request $request)
    {
	$user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success'=>'Status change successfully.']); 
    }

}
