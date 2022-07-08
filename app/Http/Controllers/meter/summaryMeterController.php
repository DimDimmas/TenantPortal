<?php

namespace App\Http\Controllers\meter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

//use AppConnection;

class summaryMeterController extends Controller
{  
    public function index() {
        return view('tenant/summary');  
    }

    public function load_data(Request $request) {
        $debtor_code = Auth::user()->tenant_code;
        $entity_project = Auth::user()->entity_project;
        $project_no = Auth::user()->project_no;
        $year = ($request->year ? $request->year : date('Y') );
        $tipe = ($request->type ? $request->type : "E");

        

        //$sql = "exec [dbo].[sp_rpt_summarChart] @entity as varchar(6), @debtor as varchar(10), @year as varchar(4), @type as varchar(4)";
        $sql = "exec [dbo].[sp_rpt_summaryChart] '".$entity_project."', '".$debtor_code."', '".$year."', '".$tipe."'"; 
        $ray = DB::select($sql);
        
        $data = array();

        foreach($ray as $val) {
            $data['bar'][] =  array('label' => $val->monthName,  'y' => round($val->usage) );
        }

        // dd($data);

        return json_encode($data, JSON_NUMERIC_CHECK);
    }
}