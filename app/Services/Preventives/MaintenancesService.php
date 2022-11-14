<?php

namespace App\Services\Preventives;

use App\Model\Preventives\Maintenance;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MaintenancesService {
    private $maintenanceModel;
    public function __construct(Maintenance $maintenanceModel)
    {
        $this->maintenanceModel = $maintenanceModel;
    }

    public function datatable($request) {
        $data = $this->maintenanceModel->getDataTable($request);
        return DataTables::of($data)
        ->editColumn('assign_to', function($datas){
            $arrayData = [
                "id" => (int) $datas->id,
                "trans_code" => $datas->trans_code,
                "assign_to" => $datas->assign_to
            ];

            $dataJson = json_encode($arrayData);
            $date = date("Y-m-d");

            $select = "";
            $select .= "<select class='form-control assign_to_table' name='assignTo$datas->id[]' id='assignTo$datas->id'
                onchange='onChangeAssignTo(event, `$dataJson`);' multiple
                style='height:30px !important;font-size:8pt !important;'
            >";

            $emps = explode(',', $datas->assign_to) ?? null;

            $emps = $emps ? DB::table("users")->whereIn('username', $emps)->get() : null;
            $techs = $emps->pluck('username')->toArray();

            // $currentUserOnWorkAreaByEntityAndProject = DB::table("bms_work_area")
            // ->join("users", "bms_work_area.username", "=", "users.username")
            // ->where('workArea', $datas->entity_project)
            // ->where('project_no', $datas->project_code)->whereRaw("CAST(scanDate AS DATE) = '$date'")
            // ->whereNull('checkOutDate')->get();

            $currentUserOnWorkAreaByEntityAndProject = DB::table("bms_work_area")->join("users", "bms_work_area.username", "=", "users.username")
            ->select("bms_work_area.username", "users.emp_name")
            ->where('workArea', $datas->entity_project)
            ->where('project_no', $datas->project_code)
            ->where("users.emp_job_position", 'Engineer')
            ->whereDate('bms_work_area.scanDate', $date)
            ->whereNull("bms_work_area.checkOutDate")
            ->whereRaw("Cast(DateDiff(hh, bms_work_area.scanDate, CURRENT_TIMESTAMP) as INT) <= 8")
            ->get();


            if($emps != null) {
                foreach($emps as $emp) {
                    $select .= "<option value='$emp->username' selected>$emp->emp_name</option>";
                }
            
                foreach($currentUserOnWorkAreaByEntityAndProject as $row)
                {
                    if(!in_array($row->username, $techs))
                    {
                        $selected = 'selected';
                        $select .= "<option value='$row->username'>$row->emp_name</option>";
                    }
                }
                $select .= "</select>";
            }

            $select = !is_null($datas->assign_to) ? $select : null;

            return $datas->status == 1 ? $select : str_replace(",", "<br />", $datas->assign_to);
        })
        ->rawColumns(['assign_to'])
        ->make(true);
    }
}