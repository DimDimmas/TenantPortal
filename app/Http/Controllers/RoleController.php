<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 function __construct()
    {
        $this->middleware('auth');
      //  $this->middleware('permission:role list', ['only' => ['index','show']]);
	//	$this->middleware('permission:role show', ['only' => ['index','show']]);
     //   $this->middleware('permission:role add', ['only' => ['create','store']]);
      //  $this->middleware('permission:role edit', ['only' => ['edit','update']]);
       // $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        //
		$roles = Role::orderBy('created_at','ASC')->paginate(10);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		$roles = Role::all();
		$permission = Permission::get();
        return view('roles.create',compact('roles,permission'));
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
		$this->validate($request, [
            'name' => 'required|unique:roles,name',
            // 'permission' => 'required',
        ]);
        $roles = Role::firstOrCreate(['name' => $request->name]);
        // $role->syncPermissions($request->input('permission'));
		$user->assignRole($request->role);
        return redirect()->route('roles.index')
                        ->with(['success' => 'Role: <strong>' . $roles->name . '</strong> created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$roles = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return view('roles.show',compact('roles',assign));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		
		$roles = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('roles.edit', compact('roles','permission'));
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
		$this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');  
    }
	public function assign(Request $request)
	{
		$roles = $request->get('roles');
		$permission = Permission::get();
		$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
		return view('roles.assign',compact('assign,roles,permission'));
	}
	public function setRolePermission(Request $request, $role)
	{
		//
		$role = Role::findByName($role);
		
		$role->syncPermissions($request->permission);
		return redirect()->back()->with(['success' => 'Permission to Role Saved!']);
	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {				
		$role = Role::findOrFail($id);
		$role->delete();
		return redirect()->back()->with(['success' => 'Role: <strong>' . $role->name . '</strong> deleted successfully']);
		}
}
