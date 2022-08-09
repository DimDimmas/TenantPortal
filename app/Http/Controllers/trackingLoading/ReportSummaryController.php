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
        // $awal = explode("/", $awal);
        // $awal = $awal[2] . "-" . $awal[1] . "-" . $awal[0];
        $akhir = $inputDate[1];
        // $akhir = explode("/", $akhir);
        // $akhir = $akhir[2] . "-" . $akhir[1] . "-" . $akhir[0];
        $query = DB::table("view_report_summary_new_bm_visit_track")->where('debtor_acct', auth()->user()->tenant_code);
        $data = $query->whereRaw("Dates >= '$awal' AND Dates <= '$akhir'")->get();
        
        return $dataTables->of(collect($data))
        ->rawColumns([])->make(true);
    }

}
