<?php

namespace App\Http\Controllers;
use App\DepartmentModel;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
	$departments = DepartmentModel::all();
        return view ('department.index')->with('name',$departments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
	return view('department.create');
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
	$request->validate([
            '' => 'required',
            'emp_status_cd' => 'required',
        ]);

        EmpStatusModel::create($request->all());

        return redirect()->route('empstatus.index')
                        ->with('success','Employee Status created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
	return view('departments.show',compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
	return view('departments.edit',compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
	 $request->validate([
            'emp_status' => 'required',
            'emp_status_cd' => 'required',
        ]);

        $departments->update($request->all());

        return redirect()->route('departmens.index')
                        ->with('success',' Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
	 $departments->delete();
        return redirect()->route('departmens.index')
                        ->with('success','Departmen deleted successfully');
    }
}
