<?php

namespace App\Http\Controllers\overtime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\overtimeModel;
use App\overtimeLogModel;
use App\overtimeUserModel;
use App\overtimeTypeModel;
use Carbon\Carbon;
use AppAction;

class overtimeController extends Controller
{
    public function create_ticket(){
        $type_overtime = DB::select('select * from bms_overtime_type where type_id > 4');
        $time_overtime = DB::select('select * from DaysTime order by times asc');
        $get_ovt_zone = DB::select('select * from bms_overtime_zone');

        return view('overtime.create', [
            'time_overtime' =>  $time_overtime,
            'type_overtime' =>  $type_overtime,
            'get_ovt_zone' => $get_ovt_zone
        ]);
    }

    public function history_ticket(){
        $overtime_list = DB::select("                
            select a.overtime_code
            ,a.overtime_date
            ,a.tenant_id
            ,c.*
            ,d.*
            ,(f.overtime_close_date) as actual_end
            ,(f.overtime_duration) as actual_duration
            ,a.overtime_status
            ,a.overtime_zone
            ,a.overtime_duration
            ,a.overtime_start
            ,a.overtime_end
            ,a.overtime_status
            ,a.overtime_approval
            ,a.overtime_approval_date
            ,a.overtime_approval_by
            ,overtime_user = (SELECT STRING_AGG( ISNULL(md.overtime_user, ' '), ', ')
                FROM bms_overtime_user md
                WHERE a.overtime_code = md.overtime_code)
            ,overtime_type = (SELECT STRING_AGG(ISNULL(cd.type_desc, ' '), ', ')
                FROM bms_overtime_details dd
                LEFT JOIN bms_overtime_type cd ON dd.overtime_type = cd.type_id
                WHERE a.overtime_code = dd.overtime_code)
            ,overtime_zone = (SELECT STRING_AGG(ISNULL(ed.overtime_zone, ' '), ', ')
                FROM bms_overtime_details ed
                WHERE a.overtime_code = ed.overtime_code)
            from bms_overtime a
                    left join bms_tenant c ON a.tenant_id = c.tenant_id
                    left join bms_status d ON a.overtime_status = d.status_id
                    left join bms_status_trx e ON a.overtime_status = e.status_id
                    left join bms_overtime_log f ON a.overtime_code = f.overtime_code and f.overtime_update_desc = 'Realisasi'
                    where e.modul = 'ot' and e.using_for = 'all' and level LIKE 'tenant%'
                    order by a.overtime_code desc
        ");
        return view('overtime.history', [
            'overtime_list' => $overtime_list
        ]);
    }

    public function get_time($date)
    {
        // $from = date_create_from_format('D M d Y H:i:s e+', $date);
        // $b = Carbon::parse($from)->format('Y-m-d');
        $a = DB::select('exec [dbo].[ovt_findtime] ?',[$date]);
        $content = '';
        foreach ($a as $key => $value) {
            $time = $value->times;
            $content .= "<option value='$time'>$time</option>"; 
        }
        return $content;
    }

    public function get_start_time($start){
        $a = DB::select('select * from DaysTime where times > ?', [$start]);
        $content = '';
        foreach ($a as $key => $value) {
            $time = $value->times;
            $content .= "<option value='$time'>$time</option>"; 
        }
        return $content;
    }

    public function get_duration(Request $request){
        // select overtime_end, overtime_start, 
        // concat((DATEDIFF(minute, overtime_start, overtime_end)/60), ':',
        //         (DATEDIFF(minute, overtime_start, overtime_end)%60))
        //   from [mmp_dev].[dbo].[bms_overtime];

        $to = Carbon::parse(str_replace("%3A",":",$request->start));
        $from = Carbon::parse(str_replace("%3A",":",$request->end));
        
        // $to = Carbon::parse($request->start);
        // $from = Carbon::parse($request->end);
        $content = '';
        $duration = $to->diff($from)->format('%H:%I:%S');
        $content .= $duration;
        return $content;
    }

    public function get_ovt_details($code)
    {
        $sql = DB::select("
                    select * from bms_overtime_details a 
                    left join bms_overtime_type b ON a.overtime_type = b.type_id
                    where overtime_code = '$code'
                    ");
        $sql_total = DB::select("SELECT sum(CAST(total_rate AS FLOAT)) as total FROM bms_overtime_details where overtime_code = '$code'");
        foreach($sql_total as $a){
            if($a->total == null){
                $echo = '';
            }else{
                if(str_contains($a->total, '.')){
                    $echo = $a->total.'00';
                }else{
                    $echo = $a->total.'.000';
                }
            }
        }
        $a = 1;
        $b = 1;
        $c = 1;
        $content = '';
        foreach($sql as $data){
            $content .= '<label class="col-sm-2 col-form-label" style="margin-bottom: 10px;">Zone '.$a++.'</label>
                        <div class="col-sm-2" style="margin-bottom: 10px;">
                            <input type="text" value="'.$data->overtime_zone.'" class="form-control" readonly>
                        </div>
                        <label class="col-sm-1 col-form-label" style="margin-bottom: 10px;">AC '.$b++.'</label>
                        <div class="col-sm-2" style="margin-bottom: 10px;">
                            <input type="text" value="'.$data->type_desc.'" class="form-control" readonly>
                        </div>
                        <label class="col-sm-1 col-form-label" style="margin-bottom: 10px;">Rate '.$c++.'</label>
                        <div class="col-sm-2" style="margin-bottom: 10px;">
                            <input type="text" value="'.$data->overtime_rate.'" class="form-control" readonly>
                        </div>
                        <div class="col-sm-2" style="margin-bottom: 10px;">
                            <input type="text"value="'.$data->total_rate.'" class="form-control" placeholder="Total Rate" readonly>
                        </div>';
        }
        $content .= '<label class="col-sm-2 col-form-label" style="margin-bottom: 10px;"></label>
                    <div class="col-sm-2" style="margin-bottom: 10px;">
                    </div>
                    <label class="col-sm-1 col-form-label" style="margin-bottom: 10px;"></label>
                    <div class="col-sm-2" style="margin-bottom: 10px;">
                    </div>
                    <div class="col-sm-1" style="margin-bottom: 10px;">
                    </div>
                    <label class="col-sm-2 col-form-label" style="margin-bottom: 10px; text-align: right">Total Rate :</label>
                    <label class="col-sm-2 col-form-label" style="margin-bottom: 10px;">
                        <strong>Rp. '.$echo.'</strong>
                    </label>';
        return $content;
    }

    public function destroy($item)
    {
        overtimeModel::where('overtime_code', $item)->delete();
        overtimeLogmodel::where('overtime_code', $item)->delete();
        overtimeUserModel::where('overtime_code', $item)->delete();
        DB::table('bms_overtime_details')->where('overtime_code', $item)->delete();

        if($item){
            return redirect()->route('history_overtime')->with(['success' => 'Data has been deleted!']);
        }else{
            return redirect()->route('history_overtime')->with(['error' => 'Failed to Delete!']);
        }
    }

    public function store(Request $request){
        // dd(count($request->typeAc));
        if(count($request->zone) != count($request->typeAc)){
            return back()->with(['error' => 'Zone or AC not selected!']);
        }
        foreach($request->zone as $key => $val){
            if($val == ''){
                return back()->with(['error' => 'Zone not selected!']);
            }
        }
        foreach($request->typeAc as $key => $val){
            if($val == ''){
                return back()->with(['error' => 'AC not selected!']);
            }
        }
        foreach($request->user as $key => $val){
            if($val == ''){
                return back()->with(['error' => 'User is empty!']);
            }
        }
        // dd(Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'));
        // $a = Carbon::parse($request->date)->format('Y-m-d');
        
        $date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateString();
        $to = Carbon::parse($request->start_time);
        $from = Carbon::parse($request->end_time);
        $duration = $to->diff($from)->format('%H:%I:%S');
        
        $codeId = AppAction::autonumber('bms_overtime', 'overtime_code', 4, 'OVT'.date("ym"));

        for($i = 0; $i < count($request->zone); $i++){
            DB::insert('insert into bms_overtime_details (
                overtime_code, 
                overtime_zone, 
                overtime_type
                ) values (?, ?, ?)', [
                $codeId,
                $request->zone[$i],
                $request->typeAc[$i]
            ]);
        }
        
        if($request->end_time >= $request->start_time){
            $overtime_insert = DB::insert('insert into bms_overtime 
                (
                    overtime_code, 
                    overtime_date, 
                    tenant_id, 
                    overtime_duration, 
                    overtime_start, 
                    overtime_end, 
                    overtime_status, 
                    overtime_approval,
                    created_date
                    ) 
                values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    $codeId,
                    $date,
                    $request->tenantId,
                    $duration,
                    Carbon::parse($request->start_time),
                    Carbon::parse($request->end_time),
                    '1',
                    '1',
                    Carbon::now()
                ]);

                $dataSet = [];
                foreach($request['user'] as $data){
                    $dataSet[] = [
                        'overtime_code' => $codeId,
                        'overtime_user' => $data,
                        'overtime_date' => $date
                    ];
                }
                $overtime_user = DB::table('bms_overtime_user')->insert($dataSet);

                $overtime_log = DB::insert('insert into bms_overtime_log 
                    (
                        overtime_code, 
                        overtime_duration, 
                        overtime_end, 
                        overtime_status, 
                        overtime_approval,
                        overtime_update_desc,
                        created_at,
                        created_by
                    ) values (?, ?, ?, ?, ?, ?, ?, ?)', [
                        $codeId,
                        $duration,
                        $request->end_time,
                        '1',
                        '1',
                        'Submit',
                        Carbon::now(),
                        $request->tenantName
                    ]);
                    
                    if($overtime_insert && $overtime_user && $overtime_log){
                        return redirect()->route('history_overtime')->with(['success' => 'Data has been saved!']);
                    }else{
                        return redirect()->route('request_overtime')->with(['error' => 'Failed!']);
                    }
        }else{
            return redirect()->route('request_overtime')->with(['error' => 'Failed!']);
        }
    }

    public function request_modify(Request $request, $overtime_code)
    {
        $request_modify = DB::table('bms_overtime')->where('overtime_code',$request->overtime_code)->update([
            'overtime_status' => 8,
            'overtime_approval' => 8
        ]);
        $request_modify_log = DB::insert('
            insert into bms_overtime_log (
                overtime_code, 
                overtime_duration,
                overtime_end,
                overtime_status,
                overtime_approval,
                overtime_update_desc,
                created_at,
                created_by
                ) 
            values (?, ?, ?, ?, ?, ?, ?, ?)', 
            [
                $overtime_code,
                $request->duration,
                $request->end,
                8,
                8,
                'Request Modify',
                Carbon::now(),
                $request->tenantPerson
            ]);
        if($request_modify && $request_modify_log){
            return redirect()->route('history_overtime')->with(['success' => 'Request Modify Send!']);
        }else{
            return redirect()->route('history_overtime')->with(['error' => 'Failed!']);
        }
    }

    public function modify_ticket($item)
    {
        $list = overtimeModel::where('bms_overtime.overtime_code', $item)
        ->leftjoin('bms_overtime_type', 'bms_overtime_type.type_id', '=', 'bms_overtime.overtime_type')
        ->select('bms_overtime.*', 'bms_overtime_type.*')
        ->first();
        $sql_user = DB::select("select * from bms_overtime_user where overtime_code = '$item'");
        $user = $sql_user;
        $user_list = $sql_user;
        $type_overtime = DB::select('select * from bms_overtime_type where type_id > 4');
        $parse_date = Carbon::parse($list->overtime_date)->format('Y-m-d');
        $get_start_time = DB::select('exec [dbo].[ovt_findtime] ?',[$parse_date]);
        $get_ovt_details = DB::select("select * from bms_overtime_details a left join bms_overtime_type b ON a.overtime_type = b.type_id where a.overtime_code = '$item'");
        $get_ovt_zone = DB::select('select * from bms_overtime_zone');
        return view('overtime.edit', [
            'list' => $list,
            'user' => $user,
            'user_list' => $user_list,
            'type_overtime' => $type_overtime,
            'get_start_time' => $get_start_time,
            'get_ovt_details' => $get_ovt_details,
            'get_ovt_zone' => $get_ovt_zone
        ]);
    }

    public function overtimeuser(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateString();
        // dd($request->zone, $request->typeAc, $request->user);
        $modified_delete_user = DB::delete("delete bms_overtime_user where overtime_code = '$request->overtime_code'");
        $dataSet = [];
        foreach($request->user as $data){
            $dataSet[] = [
                'overtime_code' => $request->overtime_code,
                'overtime_user' => $data,
                'overtime_date' => $date
            ];
        }
        $overtime_user = DB::table('bms_overtime_user')->insert($dataSet);

        return $overtime_user;
    }

    public function overtimeinsert(Request $request)
    {
        $s = Carbon::parse($request->start_time);
        $e = Carbon::parse($request->end_time);
        $duration = $s->diff($e)->format('%H:%I:%S');
        $modified = DB::table('bms_overtime')->where('overtime_code',$request->overtime_code)->update([
            'overtime_status' => 1,
            'overtime_approval' => 1,
            'overtime_start' => $request->start_time,
            'overtime_end' => $request->end_time,
            'overtime_duration' => $duration
        ]);
        
        $modified_log = DB::insert('
            insert into bms_overtime_log (
                overtime_code, 
                overtime_duration,
                overtime_end,
                overtime_status,
                overtime_approval,
                overtime_update_desc,
                created_at,
                created_by
                ) 
            values (?, ?, ?, ?, ?, ?, ?, ?)', 
            [
                $request->overtime_code,
                $duration,
                $request->end_time,
                1,
                1,
                'Submit',
                Carbon::now(),
                $request->tenantName
        ]);

        return $modified_log;
    }

    public function overtimezone(Request $request)
    {
        $delete_zone = DB::delete("delete bms_overtime_details where overtime_code ='$request->overtime_code'");
        for($i = 0; $i < count($request->zone); $i++){
            $insert_details = DB::insert('insert into bms_overtime_details (
                overtime_code, 
                overtime_zone, 
                overtime_type
                ) values (?, ?, ?)', [
                $request->overtime_code,
                $request->zone[$i],
                $request->typeAc[$i]
            ]);
        }
        return $insert_details;
    }

    public function modified_ticket(Request $request)
    {
        if($request->zone == null && $request->typeAc == null && $request->user != null){
            $query_user = $this->overtimeuser($request);
            $query_insert = $this->overtimeinsert($request);
            // dd("zone and ac is null, user not null", $request->zone, $request->typeAc, $request->user);
            if($query_user && $query_insert == true){
                return redirect()->route('history_overtime')->with(['success' => 'Data has been updated!']);
            }else{
                return redirect()->route('history_overtime')->with(['error' => 'Failed!']);
            }
        }else if($request->zone == null && $request->typeAc == null && $request->user == null){
            $query_insert = $this->overtimeinsert($request);
            // dd("zone and ac and user is null", $request->zone, $request->typeAc, $request->user);
            if($query_insert == true){
                return redirect()->route('history_overtime')->with(['success' => 'Data has been updated!']);
            }else{
                return redirect()->route('history_overtime')->with(['error' => 'Failed!']);
            }
        }else if($request->zone != null && $request->typeAc != null && $request->user != null){
            $query_user = $this->overtimeuser($request);
            $query_zone = $this->overtimezone($request);
            $query_insert = $this->overtimeinsert($request);
            // dd("zone and ac and user not null", $request->zone, $request->typeAc, $request->user);
            if($query_user && $query_zone && $query_insert == true){
                return redirect()->route('history_overtime')->with(['success' => 'Data has been updated!']);
            }else{
                return redirect()->route('history_overtime')->with(['errror' => 'Failed!']);
            }
        }else if($request->zone != null && $request->typeAc != null && $request->user == null){
            $query_zone = $this->overtimezone($request);
            $query_insert = $this->overtimeinsert($request);
            // dd("zone and ac not null, user is null", $request->zone, $request->typeAc, $request->user);
            if($query_zone && $query_insert == true){
                return redirect()->route('history_overtime')->with(['success' => 'Data has been updated!']);
            }else{
                return redirect()->route('history_overtime')->with(['error' => 'Failed!']);
            }
        }
    }
}
