<?php

namespace App\Services\Preventives;

use App\Model\Preventives\Maintenance;
use App\Model\Preventives\PmScheduleAsset;
use App\Model\Preventives\PmTaskListAssetGroup;
use App\Model\Preventives\PreventiveMaintenanceHistory;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MaintenancesService {
    private $maintenanceModel, $dateTime;
    public function __construct(Maintenance $maintenanceModel)
    {
        $this->maintenanceModel = $maintenanceModel;
        $this->dateTIme = new DateTime();
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

            }

            foreach($currentUserOnWorkAreaByEntityAndProject as $row)
            {
                if(!in_array($row->username, $techs))
                {
                    $select .= "<option value='$row->username'>$row->emp_name</option>";
                }
            }
            
            $select .= "</select>";

            // $select = !is_null($datas->assign_to) ? $select : null;

            return $datas->status == 1 ? $select : str_replace(",", "<br />", $datas->assign_to);
        })
        ->rawColumns(['assign_to'])
        ->make(true);
    }

    public function dataTableReschedule($request) {
        $data = $this->maintenanceModel->getDataTableReschedule($request);
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
            $select .= "<select class='form-control assign_to_table_reschedule' name='assignTo$datas->id[]' id='assignToReschedule$datas->id'
                onchange='onChangeAssignTo(event, `$dataJson`);' multiple
                style='height:30px !important;font-size:8pt !important;'
            >";

            $emps = explode(',', $datas->assign_to) ?? null;

            $emps = $emps ? DB::table("users")->whereIn('username', $emps)->get() : null;
            $techs = $emps->pluck('username')->toArray();

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

            // $select = !is_null($datas->assign_to) ? $select : null;

            return $datas->status == 1 ? $select : str_replace(",", "<br />", $datas->assign_to);
        })
        ->editColumn("schedule_date", function($datas) {
            $arrayData = [
                "id" => (int) $datas->id,
                "trans_code" => $datas->trans_code,
                "schedule_date" => $datas->schedule_date,
            ];

            $arrayData = json_encode($arrayData);
            $date = is_null($datas->schedule_date) ? null : date('Y-m-d', strtotime($datas->schedule_date));
            
            $html = "<input type='date' class='text-dark' name='schedule_date$datas->id' 
                id='schedule_date$datas->id' value='$date' onchange='changeScheduleDate(event, `$arrayData`);'
            />";
            return $html;
        })
        ->rawColumns(['assign_to', 'schedule_date'])
        ->make(true);
    }

    public function reschedule($id, $request) {
        $results = [];
        DB::beginTransaction();
        try {
            $preventiveMaintenace = $this->maintenanceModel->with("asset")->findOrFail($id);
            
            if(is_null($preventiveMaintenace->asset)) throw new \Exception("Data tidak ditemukan", 404);

            $schedule_date = !is_null($request->schedule_date) ? new DateTime($request->schedule_date) : null;
        
            $preventiveMaintenace->schedule_date = !is_null($schedule_date) ? $schedule_date->format("Y-m-d") : null;
            $preventiveMaintenace->due_date = !is_null($schedule_date) ? $schedule_date->add(new DateInterval("P".$preventiveMaintenace->asset->due_days."D"))->format("Y-m-d") : null;
            $preventiveMaintenace->created_by = auth()->user() ? auth()->user()->tenant_code : '[System]';
            $preventiveMaintenace->updated_by = auth()->user() ? auth()->user()->tenant_code : '[System]';
            $save = $preventiveMaintenace->save();
            if(!$save) throw new \Exception("Terjadi kesalahan dalam memproses data. Harap hubungi administrator.", 500);

            // update pm schedule asset
            $findSchedule = PmScheduleAsset::whereEntityProject($preventiveMaintenace->entity_project)
                ->whereProjectCode($preventiveMaintenace->project_code)
                ->wherePmAssetDetailId($preventiveMaintenace->pm_asset_detail_id)
                ->wherePmScheduleDate($preventiveMaintenace->schedule_date)->first()
            ;

            if($findSchedule)  $findSchedule->update(['pm_schedule_date'=>!is_null($schedule_date) ? $schedule_date->format("Y-m-d") : null]);

            // refresh data preventive after update
            $preventiveMaintenace->refresh();
            // convert to array
            $dataPreventive = $preventiveMaintenace->toArray();
            unset($dataPreventive['created_at']);
            unset($dataPreventive['created_by']);
            unset($dataPreventive['updated_at']);
            unset($dataPreventive['updated_by']);

            (new PreventiveMaintenanceHistory)->insertLog($dataPreventive);

            DB::commit();
            $results = [
                "error" => false,
                "code" => 200,
                "header" => "Success",
                "message" => "Data berhasil disimpan.",
            ];
        } catch(\Exception $err) {
            DB::rollBack();
            $results = [
                "error" => true,
                "code" => $err->getCode(),
                "header" => "Error",
                "message" => $err->getMessage(),  
            ];
        }
        return $results;
    }

    public function changeAssignTo($id, $request) {
        $results = [];
        DB::beginTransaction();
        try {
            $preventive = $this->maintenanceModel->with("asset_group", "asset", "asset_detail", "check_lists")->findOrFail($id);

            if(is_null($preventive->check_lists)) {
                $shareTaskService = new ShareTaskService($this->maintenanceModel, new PmTaskListAssetGroup());
                $shareTaskService->insertDataTaskGroupAndTaskDetail($preventive);
            }

            $assignDate = !is_null($request->input) ? date("Y-m-d") : null;
            $emps = $request->input;
            $emps = !empty($emps) ? implode(",", $emps) : null;
            $data = [
                "assign_to" => $emps,
                "assign_date" => $assignDate,
                "updated_at" => $this->dateTime,
                "updated_by" => auth()->user() ? auth()->user()->tenant_code : '[System]',
            ];
            
            if(!$preventive->update($data)) throw new \Exception("Terjadi kesalahan dalam memproses data. Harap hubungi administrator.", 500);

            $preventive->refresh();
            $arrayPreventive = $preventive->toArray();
            unset($arrayPreventive['created_at']);
            unset($arrayPreventive['created_by']);
            unset($arrayPreventive['updated_at']);
            unset($arrayPreventive['updated_by']);

            (new PreventiveMaintenanceHistory())->insertLog($arrayPreventive);

            DB::commit();
            $results = [
                "error" => false,
                "code" => 200,
                "header" => "Success",
                "message" => "Data berhasil disimpan.",
            ];
        } catch(\Exception $err) {
            DB::rollBack();
            $results = [
                "error" => true,
                "code" => $err->getCode(),
                "header" => "Error",
                "message" => $err->getMessage(),  
            ];
        }

        return $results;
    }

    public function dataTableHistories($request) {
        $data = $this->maintenanceModel->getDataTableHistories($request);
        return DataTables::of($data)
        ->rawColumns([])
        ->make(true);
    }
}