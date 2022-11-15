<?php

namespace App\Http\Controllers\Preventives;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DoneMaintenanceController extends Controller
{
    public function index(){
        return view("preventives.done_maintenances.index");
    }

    public function dataTableHistories(Request $request, DataTables $dataTables) {
        $data = DB::table("view_transaksi_preventive_maintenance_done");
        // cek kondisi lazada
        $userEntity = trim(auth()->user()->entity_project) ?? null;
        $userProject  = trim(auth()->user()->project_no) ?? null;
        $userTenant  = trim(auth()->user()->tenant_id) ?? null;
        $data = $data
            ->where('entity_project', $userEntity)->where('project_code', $userProject)
            // ->where("tenant_id", $userTenant)
        ;
        
        return $dataTables->query($data)
        ->rawColumns([])->make(true);
    }
}
