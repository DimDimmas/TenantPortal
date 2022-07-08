<?php

namespace App\Http\Controllers;

use App\DivisionModel;
use Illuminate\Http\Request;


class DivisionController extends Controller
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
	    $divisions = DivisionModel::all();
	    return view('division.index')->with('name',$divisions);        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
	return view('division.create');
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
            'div_name' => 'required',
	        'div_cd' => 'required',
	        'div_desc' => 'required',
        ]);

        DivisionModel::create($request->all());
        return redirect()->route('divisions.index')
                        ->with('success','Division  created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Division $division)
    {
        //
	return view('division.show',compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DivisionController $division)
    {
        //
	return view('division.edit',compact('division'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $request->validate([
            'div_name' => 'required',
            'div_cd' => 'required',
            'div_desc' => 'required',
        ]);
        $division->update($request->all());
        return redirect()->route('divisions.index')
                        ->with('success',' Division updated successfully');
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
        $division->delete();
        return redirect()->route('divisions.index')
                        ->with('success','Division deleted successfully');
    }
}
