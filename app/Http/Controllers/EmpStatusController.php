<?php

namespace App\Http\Controllers;

use App\EmpStatusModel;
use Illuminate\Http\Request;

class EmpStatusController extends Controller
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
	$empstatuss = EmpStatusModel::all();
        return view('empstatus.index')->with('name',$empstatuss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
	return view('empstatus.create');
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
            'emp_status' => 'required',
            'emp_status_cd' => 'required',
        ]);
  
        EmpStatusModel::create($request->all());
   
        return redirect()->route('empstatus.index')
                        ->with('success','Employee Status created successfully.');
//	 return view('status.store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EmpStatusModel $empstatus)
    {
        //
	return view('empstatus.show',compact('empstatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpStatusModel $empstatus)
    {
        //
//	$empstatus =  EmpStatusModel::all();
	return view('empstatus.edit',compact('empstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpStatusModel $empstatus)
    {
        //
	$request->validate([
            'emp_status' => 'required',
            'emp_status_cd' => 'required',
        ]);
  
        $empstatus->update($request->all());
  
        return redirect()->route('empstatus.index')
                        ->with('success',' Employee Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpStatusModel $empstatus)
    {
        //
	$empstatus->delete();
	return redirect()->route('empstatus.index')
                        ->with('success','Empstatus deleted successfully');

    }
}
