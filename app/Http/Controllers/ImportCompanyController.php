<?php

namespace App\Http\Controllers;
use App\CompanyModel;
use Excel;
use Illuminate\Http\Request;

class ImportCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
	$select = CompanyModel::all();
        return view ('importcompany')->with('name',$select);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
	return view('importcompany.show',compact('importcompany'));
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
	 public function import(Request $request)
    {
        $this->validate($request,[
                'select_file' => 'required|mimes:xls,xlsx'
        ]);
        $path = $request->file('select_file')->getRealPath();
        $data = Excel::load($path)->get();
        if ($data->count() > 0 )
        {
                foreach($data->toArray() as $key  => $value)
                {
                        foreach($value as $row)
                        {
                                $insert_data[] = array(
                                        'company' => $row['entity_name'],
                                        'entity_cd' => $row['entity_cd'],
                                        'address1' => $row['address1'],
                                        'address2' => $row['address2'],
                                        'city' => $row['city' ],
                                        'siup_no' => $row['siup_no'],
                                        'npwp' => $row['npwp'],
                                        'listed_date' => $row['listed_date'],
                                        'currency_cd' => $row['currency_cd' ],
                                        'entity_parent' => $row['entity_parent'],
                                        'active_date' => $row['active_date'],
                                        'end_date' => $row['end_date' ],
                                        'period' => $row['period' ],
                                        'tax_cd' => $row['tax_cd' ],
                                        'status' => $row['status' ],

                                );
                        }
                }
                if ( !empty($insert_data))
                {
                        DB::table('company_tbl')->insert($insert_data);

                }
        }
        return back()->with('succes','Data Company Inported Succesfully.');
    }
}
