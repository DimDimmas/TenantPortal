<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportSummaryController extends Controller
{
    public function index()
    {
        $this->data['tenant'] = $this->getTenantCompanyByCurrentUser();
        return view('tracking_loading.report_summary.index', $this->data);
    }

    public function getDataTable(Request $request, DataTables $dataTables) {
        $inputDate = explode(" - ", $request->dateSelected);
        $awal = $inputDate[0];
        $akhir = $inputDate[1];
        $query = DB::table("view_report_summary_bm_visit_track");
        $data = $query->where('debtor_acct', auth()->user()->tenant_code)->whereRaw("date BETWEEN '$awal' AND '$akhir'")->get();
        
        return $dataTables->of(collect($data))
        ->rawColumns([])->make(true);
    }

}
