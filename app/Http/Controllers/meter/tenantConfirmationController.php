<?php

namespace App\Http\Controllers\meter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

//use AppConnection;

class tenantConfirmationController extends Controller
{   
    public function index($param) {
        $decode = base64_decode($param."==");
        $param = explode("::",$decode);
        $Where = '';
        $whereCount = array();
        
        if(count($param) == '3') {
            if($param[0]) {
                $meterID = $param[0];
            } else {
                return abort(403,"You don't have permission in this page");
            }

            if($param[1]) {
                $month = $param[1];
            } else {
                return abort(403,"You don't have permission in this page");
            }

            if($param[2]) {
                $year = $param[2];
            } else {
                return abort(403,"You don't have permission in this page");
            }

            $Where = " WHERE a.meter_id = '".$meterID."' AND month(b.curr_read_date) = '".$month."' AND year(b.curr_read_date) = '".$year."'";
        }

        $whereCount[] = array('meter_id', '=', $meterID);
        $whereStatus = array('H', 'S');

        $countData = DB::table('bms_meter_sign')->where($whereCount)->whereIn('status', $whereStatus)->count();
        
        if($countData < 1) {
            abort(403,"You don't have permission in this page");
        }

        $sql = "SELECT 
                (SELECT entity_name FROM [dbo].[ifca_cf_entity] WHERE entity_project = concat(a.entity_cd, a.db_identity) ) as entity, 
                a.debtor_acct, lot_no, debtor_name, 
                a.meter_id, CASE WHEN type = 'E' THEN 'Electricity' ELSE 'Water' END AS type, 
                last_read, last_read_high, last_read_date, 
                curr_read, curr_read_high, b.curr_read_date, 
                attachment, b.status, b.rowID FROM bms_meter a 
                LEFT JOIN bms_meter_sign b ON a.entity_cd = b.entity_cd AND a.debtor_acct = b.debtor_acct AND a.meter_id = b.meter_id";
        $sql .= $Where;
        $data = DB::select($sql);
        if($data) {
            $pic = $data[0]->attachment;
            $dataPic = explode(";;", $pic);
        } else {
            return abort(403);
        }
        return view('tenant/index', ['data' => $data, 'images' => $dataPic, "uri" => 'https://tenantportal.mmproperty.com/img/bms/photo/' ] );

    }

    public function list_confirm() {
        return view('tenant/listconfirmation');
    }

    public function update(Request $request)
    {   
        $where = array();
        $colUpdate = array();

        $tenantConfirm = "SELECT COALESCE(count(*),0) as count FROM [mmp_dev].[dbo].[bms_meter_sign] WHERE status = 'H' AND meter_id = '".$request->meterId."'";
        $tenantConfirm = DB::select($tenantConfirm);

        if($tenantConfirm) {
            $where[] = array('rowID', '=', $request->row);
            $where[] = array('meter_id', '=', $request->meterId);
            $where[] = array('status', '=', 'H');

            $colUpdate = array('status' => 'S', 'sign_date' => date('Y-m-d'));
           // $tenantUpdate = "UPDATE [mmp_dev].[dbo].[bms_meter_sign] SET status = 'S' AND sign_date = GETDATE() WHERE status = 'H' AND meter_id = '".$request->meterId."'";
            DB::table("mmp_mynet.dbo.bms_meter_sign")->where($where)->update($colUpdate);
        }
       
        $previousUrl = app('url')->previous();

        return redirect()->to($previousUrl)->with('success', 'Success updating confirmation.');
        
    }

    public function grid_confirmation(Request $request) {
        // $type = $request->meter_type;
        // $StartDate = date("Y-m-d",strtotime($request->StartDate));
        // $EndDate = date("Y-m-d",strtotime($request->EndDate));

        // $status = $request->status;
        $pagenumbers = $request->page;
        $rowpages = $request->rows;
        $filter = json_decode($request->filterRules);
        $cond = array();
        $condQuery = '';

        if($filter <> null) {
            foreach ($filter as $key => $value) {
                $cond[] = $value->field . " LIKE '%".$value->value."%'";
            }
            $condQuery = " AND ".implode(" AND ", $cond);
        }

        $sql = "SELECT * FROM (
                SELECT 
                c.entity_name as entity_name
                ,d.descs as descs
                ,(SELECT ref_no FROM [dbo].[ifca_pm_lot_meter] WHERE entity_cd = a.entity_cd AND project_no = a.project_no AND meter_id = a.meter_id ) as panel
                ,a.entity_cd
                ,a.project_no
                ,a.debtor_acct
                ,a.lot_no
                ,a.debtor_name
                ,a.meter_id
                ,a.meter_cd
                ,a.type
                ,a.last_read_date
                ,CONVERT(DECIMAL(10,3),a.last_read) as last_read
                ,CONVERT(DECIMAL(10,3), a.last_read_high) as last_read_high
                ,a.curr_read_date
                ,CONVERT(DECIMAL(10,3), a.curr_read) as curr_read
                ,CONVERT(DECIMAL(10,3), a.curr_read_high) as curr_read_high
                ,month(a.curr_read_date) as month
                ,year(a.curr_read_date) as year
                ,a.flag
                ,a.attachment
                ,a.tenant_name
                ,a.signature
                ,a.correction
                ,a.read_by
                ,a.verify_by
                ,a.verify_date
                ,a.longitude
                ,a.latitude
                ,b.status
                ,a.rowID
                FROM [mmp_mynet].[dbo].[bms_meter] a 
                JOIN [mmp_mynet].[dbo].[bms_meter_sign] b ON a.entity_cd = b.entity_cd AND a.debtor_acct = b.debtor_acct AND a.meter_id = b.meter_id  and a.rowID = b.rowID
                LEFT JOIN [dbo].[ifca_cf_entity] c ON concat(a.entity_cd,a.db_identity) = c.entity_project
                LEFT JOIN [dbo].[ifca_pl_project] d ON concat(a.entity_cd,a.db_identity) = d.entity_project AND a.project_no = d.project_no
                ) ax
                WHERE status IN ('S', 'H') 
                AND meter_id NOT IN (SELECT waste_id FROM dbo.ifca_pm_lot_meter WHERE waste_id IS NOT NULL)
                AND debtor_acct = '".Auth::user()->tenant_code."' ". $condQuery . " ORDER BY curr_read_date ASC
                OFFSET ($pagenumbers-1)*$rowpages ROWS
                FETCH NEXT $rowpages ROWS ONLY
                ";

        // dd($sql);        
        $data =  DB::select($sql);
            
        $result = array();
        foreach ($data as $row => $value) {
            $dtx = (array) $data[$row];
            foreach ($dtx as $key => $value) {
                $result[$row][$key] = $value;
            }
            $result[$row]['link'] = str_replace("==","",base64_encode($data[$row]->meter_id.'::'.$data[$row]->month.'::'.$data[$row]->year));

        }
        return json_encode($result);

    }

    public function list_history() {
        return view('tenant/listhistory');
    }

    public function grid_history(Request $request) {
        $pagenumbers = $request->page;
        $rowpages = $request->rows;
        $filter = json_decode($request->filterRules);
        $cond = array();
        $condQuery = '';

        if($filter <> null) {
            foreach ($filter as $key => $value) {
                $cond[] = $value->field . " LIKE '%".$value->value."%'";
            }
            $condQuery = implode(" AND ", $cond);
        }
        $cond = '';
        if($condQuery <> ''){
          $cond .= 'WHERE '.$condQuery;  
        } 

        $datefrom = explode("/",$request->datefrom);
        $datefrom = $datefrom[2]."-".$datefrom[1]."-".$datefrom[0];
        $dateto = explode("/",$request->dateto);
        $dateto = $dateto[2]."-".$dateto[1]."-".$dateto[0];

        $sql = "SELECT * FROM ( 
                SELECT a.*, b.ref_no as panel,
                CONVERT(varchar(10),a.read_date,126) as date_read,
                (SELECT entity_name FROM [dbo].[ifca_cf_entity] WHERE entity_project = a.entity_project) as entity_name,
                (SELECT descs FROM [dbo].[ifca_pl_project] WHERE entity_project = a.entity_project AND project_no = a.project_no) as project_name,
                (SELECT name FROM [dbo].[ifca_ar_debtor] WHERE entity_project = a.entity_project AND project_no = a.project_no AND debtor_acct = a.debtor_acct) as debtor_name,
                (SELECT attachment FROM [dbo].[bms_meter] WHERE entity_cd = a.entity_cd AND project_no = a.project_no AND debtor_acct = a.debtor_acct AND meter_id = a.meter_id AND CONVERT(varchar(10),curr_read_date,126) = CONVERT(varchar(10),a.read_date,126)) as attachment,
                (SELECT signature FROM [dbo].[bms_meter] WHERE entity_cd = a.entity_cd AND project_no = a.project_no AND debtor_acct = a.debtor_acct AND meter_id = a.meter_id AND  CONVERT(varchar(10),curr_read_date,126) = CONVERT(varchar(10),a.read_date,126)) as signature,
                (SELECT tenant_name FROM [dbo].[bms_meter] WHERE entity_cd = a.entity_cd AND project_no = a.project_no AND debtor_acct = a.debtor_acct AND meter_id = a.meter_id AND  CONVERT(varchar(10),curr_read_date,126) = CONVERT(varchar(10),a.read_date,126)) as tenant_name,
                (SELECT dbo.fnc_username(read_by) FROM [dbo].[bms_meter] WHERE entity_cd = a.entity_cd AND project_no = a.project_no AND debtor_acct = a.debtor_acct AND meter_id = a.meter_id AND  CONVERT(varchar(10),curr_read_date,126) = CONVERT(varchar(10),a.read_date,126)) as capture_by
                FROM [dbo].[ifca_pm_meter_dtl_his] a LEFT JOIN [dbo].[ifca_pm_lot_meter] b
                ON a.entity_cd = b.entity_cd AND a.project_no = b.project_no AND a.debtor_acct = b.debtor_acct AND a.meter_id = b.meter_id
                WHERE a.entity_project = '".Auth::user()->entity_project."' AND CONVERT(varchar,a.read_date,126) BETWEEN '".$datefrom."' AND '".$dateto."' AND a.debtor_acct = '".Auth::user()->tenant_code."'
                ) ax  " .$cond. " ORDER BY read_date ASC
                OFFSET ($pagenumbers-1)*$rowpages ROWS
                FETCH NEXT $rowpages ROWS ONLY";
        // dd($sql);

        $data =  DB::select($sql);
        return $data;

    }

}