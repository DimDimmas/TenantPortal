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
        $awal = explode("/", $awal);
        $awal = $awal[2] . "-" . $awal[1] . "-" . $awal[0];
        $akhir = $inputDate[1];
        $akhir = explode("/", $akhir);
        $akhir = $akhir[2] . "-" . $akhir[1] . "-" . $akhir[0];
        $query = DB::table("view_report_summary_new_bm_visit_track")->where('debtor_acct', auth()->user()->tenant_code);
        $data = $query->whereRaw("convert(date, Dates) >= '$awal' and convert(date, Dates) <= '$akhir'")->get();
        // $data = DB::select($this->query($awal, $akhir, auth()->user()->tenant_code));
        
        return $dataTables->of(collect($data))
        ->rawColumns([])->make(true);
    }

    private function query($awal, $akhir, $debtor_acct) {
        return "SELECT debtor_acct, debtor_name, entity_project, Dates, Datenames, entity_name, Tenant, count(1) as CountofVihicle,
        sum(time_1) as time_1, sum(time_2) as time_2, sum(time_3) as time_3, sum(time_4) as time_4, sum(time_5) as time_5,
        sum(NotScanOut) as NotScanOut, 
        
        convert(varchar(max), convert(numeric(18,2),(convert(numeric(18,2),avg(DATEDIFF(minute,scan_in,(case when scan_out is null then getdate() else scan_out end))))/60))) as AvginWH, 
        convert(varchar(max), convert(numeric(18,2),convert(numeric(18,2),min(DATEDIFF(minute,scan_in,(case when scan_out is null then getdate() else scan_out end))))/60)) as Min_in_WH,
        convert(varchar(max), convert(numeric(18,2),convert(numeric(18,2),max(DATEDIFF(minute,scan_in,(case when scan_out is null then getdate() else scan_out end))))/60)) as Max_in_WH
        
        FROM (
        SELECT
                a.debtor_acct, 
                a.debtor_name,
                a.entity_project,
                convert(varchar, a.scan_in, 103) as Dates, 
                datename(dw,a.scan_in) as Datenames,
                a.entity_name, 
                trim(upper(a.debtor_name))  as Tenant , 
            scan_in,
            scan_out,
                --count(1) as CountofVihicle , 
            CASE WHEN CAST(scan_in as time) BETWEEN '00:00:00' AND '06:59:59' THEN '1' ELSE 0 END as time_1,
            CASE WHEN CAST(scan_in as time) BETWEEN '07:00:00' AND '11:00:59' THEN '1' ELSE 0 END as time_2,
            CASE WHEN CAST(scan_in as time) BETWEEN '11:01:00' AND '15:00:59' THEN '1' ELSE 0 END as time_3,
            CASE WHEN CAST(scan_in as time) BETWEEN '15:01:00' AND '19:00:59' THEN '1' ELSE 0 END as time_4,
            CASE WHEN CAST(scan_in as time) BETWEEN '19:01:00' AND '23:59:59' THEN '1' ELSE 0 END as time_5,
            case when a.scan_out is null then 1 else 0 end as NotScanOut 
                    
                from 
                    view_bm_visit_track as a
                where convert(date, scan_in) >= '$awal' and convert(date, scan_in) <= '$akhir'
                AND a.debtor_acct = '" . $debtor_acct . "'
        ) ax group by ax.debtor_acct, ax.debtor_name, ax.entity_project, ax.entity_name, ax.Tenant, Dates, Datenames";
    }

}
