<?php

namespace App\Http\Controllers;
use App\EmpPositionModel;
use Illuminate\Http\Request;

class EmpPositionController extends Controller
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
        $emppositions = EmpPositionModel::all();
        return view('empposition.index')->with('name',$emppositions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('empposition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
        //
        $request->validate([
            'position_name' => 'required',
            'position_cd' => 'required',
            'position_desc' => 'required',
        ]);
  
        EmpPositionModel::create($request->all());
        return redirect()->route('emppositions.index')
                        ->with('success','Employee Position created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EmpPositionModel $empposition )
    {
        //
        return view('empposition.show',compact('empposition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpPositionModel $empposition )
    {
        //
        return view('empposition.edit',compact('empposition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpPositionModel $empposition)
    {
        //
        $request->validate([
            'position_name' => 'required',
            'position_cd' => 'required',
            'position_desc' => 'required',
        ]);
  
        $empposition->update($request->all());
        return redirect()->route('emppositions.index')
                        ->with('success',' Employee Position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpPositionModel $empposition )
    {
        //
        $empposition->delete();
	    return redirect()->route('emppositions.index')
                        ->with('success','Positions deleted successfully');
    }
}
