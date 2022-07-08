<?php

namespace App\Http\Controllers\corrective;

use App\Http\Controllers\Controller;
use App\requestTicket;
use App\complaintActionTaken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use AppAction;
use AppModel;
use AppConnection;
use \Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use File;


class ticketController extends Controller
{
    public function create_ticket(){
        $tenant_id = "SELECT * FROM [mmp_mynet].[dbo].[bms_tenant]";
        $tenant_id = DB::select($tenant_id);

        // $form_id = "SELECT * FROM [mmp_dev].[dbo].[bms_ticket_form]";
        // $form_id = DB::select($form_id);

        // $form_id = "SELECT * FROM [mmp_dev].[dbo].[bms_ticket_form]";
        $form = DB::table('bms_ticket_form')->orderBy("form_id", "desc")->get();
        // $type_id = "SELECT * FROM [mmp_dev].[dbo].[bms_ticket_type]";
        // $type_id = DB::select($type_id);

        return view('corrective.createticket', [
            'tenant_id' => $tenant_id,
            'form' => $form,
            // 'type_id' => $type_id,
        ]);
    }

    public function get_type_id(Request $request){
        //dd($request->trigger);
        $id = $request->trigger;
        $id = ($id == '1' ? 3 : 2);
        // $qry = DB::table('bms_ticket_category')->where("type_id", $id);
        // $sql = "SELECT * FROM [mmp_dev].[dbo].[bms_ticket_category]";
        
        $sql = "select * from bms_ticket_category where type_id = '$id'";
        $result =  DB::select($sql);
        $resp = [];
        foreach($result as $result){
            $arrayData['results'][] = array(
                      'id' => $result->category_id,
                      'text' => $result->category_desc
                    );
        }
        return json_encode($arrayData);
    }
    
    public function store(Request $request){
        // dd($request->tenantTicketAttachment[0]);
        // dd(date('Y-m-d H:i:s'));
        $this->validate($request, [
            'tenantTicketAttachment' => 'image|mimes:png,jpg,jpeg'
        ]);
        
        $codeId = AppAction::autonumber('bms_tenant_ticket', 'tenant_ticket_id', 4, 'C'.date("ym"));

        $image_attachment = array();
        $imageName = 'CM-0-'.time().'.'.$request->file('tenantTicketAttachment')->getClientOriginalExtension();
        $request->file('tenantTicketAttachment')->move(public_path('img/bms/photo/'), $imageName);
        $image_attachment = $imageName;

        $req_ticket = DB::insert('insert into bms_tenant_ticket
            (
                tenant_ticket_id,
                tenant_id, 
                form_id, 
                category_id, 
                tenant_ticket_location, 
                tenant_ticket_description,
                tenant_ticket_attachment,
                tenant_ticket_post,
                status_id,
                status_tenant,
                entity_project,
                entity_cd,
                project_no,
                created_by
                ) 
            values (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )', [
                $codeId,
                $request->tenantId,
                $request->formId,
                $request->cateId,
                $request->tenantTicketLocation,
                $request->tenantTicketDesc,
                $image_attachment,
                date('Y-m-d H:i:s'),
                '1',
                '1',
                $request->entityProject,
                $request->entityCd,
                $request->projectNo,
                $request->tenantCode
            ]);

            $sql ="SELECT username FROM PMAssignmentArea a LEFT JOIN users b ON a.staff_name = b.username WHERE a.entity_project = '".$request->entityProject."'";
            // $res = Collect(\DB::select($sql))->first();
            $res = DB::select($sql);

            foreach ($res as $res) {
                if($res->username <> null) {
    
                $SQL = "INSERT INTO bms_assignment_pic
                        (tenant_ticket_id
                        ,assignment_seq
                        ,engineer_username
                        ,assignment_from
                        ,assignment_response
                        ,created_date
                        ,assignment_status)
                        VALUES 
    
                        (
                            '".$codeId."'
                            , '1'
                            , '".$res->username."'
                            , '".$request->tenantCode."'
                            , '".date('Y-m-d H:i:s')."'
                            , '".date('Y-m-d H:i:s')."'
                            , 'A'
                        )
                        ";
    
                    $result = DB::insert($SQL);
                }
            }
            // End Update Code By : Haris 09-11-2021 //
            
            // $result = DB::insert('
            // INSERT INTO [dbo].[bms_assignment_pic]
            // (tenant_ticket_id
            // ,assignment_seq
            // ,engineer_username
            // ,assignment_from
            // ,assignment_response
            // ,created_date
            // ,assignment_status)
            // VALUES (?, ?, ?, ?, ?, ?, ?)', [
            //     $codeId
            //     , '1'
            //     , $res->username
            //     , $request->tenantCode
                // , date('Y-m-d H:i:s')
                // , date('Y-m-d H:i:s')
            //     , 'A'
            // ]);

            if($req_ticket){
                return redirect()->route('history_ticket')->with(['success' => 'Data has been saved!']);
            }else{
                return redirect()->route('request_ticket')->with(['error' => 'Create ticket failed!']);
            }
    }

    public function destroy($item){
        $img = requestTicket::where('tenant_ticket_id', $item)->first();
        File::delete('img/bms/photo/'.$img->tenant_ticket_attachment);

        requestTicket::where('tenant_ticket_id', $item)->delete();
        DB::table('bms_assignment_pic')->where('tenant_ticket_id', $item)->delete();
        
        if($item){
            return redirect()->route('history_ticket')->with(['success' => 'Data has been deleted!']);
        }else{
            return redirect()->route('history_ticket')->with(['error' => 'Failed to Delete!']);
        }
    }

    public function history_ticket(){
            $ticket_list = DB::select("
                select *, 
                CASE WHEN a.ticket_by = 'T' THEN (SELECT company_name FROM bms_tenant_company WHERE tenant_code = (SELECT tenant_code FROM bms_tenant WHERE tenant_id = a.tenant_id AND entity_project = a.entity_project AND project_no = a.project_no) AND entity_project = a.entity_project AND project_no = a.project_no  ) ELSE (SELECT emp_name FROM users WHERE username = a.tenant_id) END as company_name
                from bms_tenant_ticket a
                left join bms_status d ON a.status_tenant = d.status_id
                left join bms_ticket_form e ON a.form_id = e.form_id
                left join bms_ticket_category f ON a.category_id = f.category_id
                left join bms_ticket_type g ON a.type_id = g.type_id
                left join bms_status_trx h ON a.status_tenant = h.status_id where h.modul = 'cm' and level LIKE 'tenant%'
                ORDER BY a.tenant_ticket_id desc
            ");
        return view('corrective.historyticket', [            
            'ticket_list' => $ticket_list
        ]);
    }

    public function show($item)
    {        
        $code = $item;
        $a = requestTicket::where('bms_tenant_ticket.tenant_ticket_id', $item);
        $list = $a
                ->leftjoin('bms_status_trx', 'bms_status_trx.status_id', '=', 'bms_tenant_ticket.status_tenant')
                ->leftjoin('bms_status', 'bms_status.status_id', '=', 'bms_status_trx.status_id')
                ->leftjoin('bms_ticket_form', 'bms_ticket_form.form_id', '=', 'bms_tenant_ticket.form_id')
                ->leftjoin('bms_ticket_type', 'bms_ticket_type.type_id', '=', 'bms_tenant_ticket.type_id')
                ->leftjoin('bms_ticket_category', 'bms_ticket_category.category_id', '=', 'bms_tenant_ticket.category_id')
                ->leftjoin('bms_tenant', 'bms_tenant.tenant_id', '=', 'bms_tenant_ticket.tenant_id')
                ->leftjoin('bms_tenant_company', 'bms_tenant_company.tenant_code', '=', 'bms_tenant.tenant_code', 'and', 'bms_tenant_company.entity_cd', '=', 'bms_tenant.entity_cd')
                ->leftjoin('bms_action_taken', 'bms_action_taken.tenant_ticket_id', '=', 'bms_tenant_ticket.tenant_ticket_id')
                ->select('bms_tenant_ticket.*', 'bms_tenant.*', 'bms_status.status_name', 'bms_ticket_form.form_desc', 'bms_ticket_type.type_desc', 'bms_ticket_category.category_desc', 'bms_tenant_company.*', 'bms_action_taken.*')
                ->first();
        $activity = DB::select("select * from bms_action_taken where tenant_ticket_id = '$item'");
        $get_engineer = DB::table('bms_action_taken')->where('tenant_ticket_id', $item)->get();
        // $get_engineer_1 = DB::table('bms_assignment_pic')->where('tenant_ticket_id', $item)->where('assignment_seq', '1')->get();
        // $get_engineer_2 = DB::table('bms_assignment_pic')->where('tenant_ticket_id', $item)->where('assignment_seq', '2')->get();
        
        $get_engineer_1 = DB::select("
                            select emp_name from bms_assignment_pic a, users b
                            where a.engineer_username = b.username
                            AND tenant_ticket_id = '".$item."'
                            AND assignment_seq = '2'
        ");
        $get_engineer_2 = DB::select("select DISTINCT emp_name from bms_action_taken a, users b
                            where a.engineering_username = b.username AND tenant_ticket_id = '".$item."'");

        // $get_spv = DB::table('bms_assignment_pic')->where('tenant_ticket_id', $item)->get();
        return view('corrective.showticket', [
            'list' => $list,
            'code' => $code,
            'activity' => $activity,
            'get_engineer' => $get_engineer,
            'get_engineer_1' => $get_engineer_1,
            'get_engineer_2' => $get_engineer_2,
            // 'get_spv' => $get_spv,
        ]);
    }

    public function update(Request $request)
    {
        $update = DB::table('bms_tenant_ticket')->where('tenant_ticket_id',$request->tenant_ticket_id)->update([
            'status_id' => 10,
            'status_tenant' => 10
        ]);
        
        if($update){
            return redirect()->route('history_ticket')->with(['success' => 'Ticket has been closed!']);
        }else{
            return redirect()->route('history_ticket')->with(['error' => 'Failed!']);
        }
    }
}
