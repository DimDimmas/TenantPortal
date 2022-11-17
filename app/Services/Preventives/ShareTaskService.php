<?php
namespace App\Services\Preventives;

use App\Model\Preventives\Maintenance;
use App\Model\Preventives\PmAssetDetail;
use App\Model\Preventives\PmTaskListAssetGroup;
use App\Model\Preventives\PmTaskListGroupDetail;
use App\Model\Preventives\PreventiveMaintenanceDetail;
use App\Model\Preventives\PreventiveMaintenanceGroup;
use App\Model\Preventives\PreventiveMaintenanceHistory;
use DateTime;
use Illuminate\Support\Facades\DB;

class ShareTaskService {
    private $preventiveMaintenace, $pmTaskListAssetGroup, $dateTime;

    public function __construct(
        Maintenance $preventiveMaintenace, PmTaskListAssetGroup $pmTaskListAssetGroup
    ) {
        ini_set('max_execution_time', '99999999999999999999999999999');
        $this->preventiveMaintenace = $preventiveMaintenace;
        $this->pmTaskListAssetGroup = $pmTaskListAssetGroup;
        $this->dateTime = new DateTime();
    }

    public function shareTaskToPersonInCharges() {
        DB::beginTransaction();
        $results = [];
        try {
            $workAreaAndProjectNo = $this->getWorkAreaAndProjectNo();
            if(count($workAreaAndProjectNo) == 0) 
                throw new \Exception("Tidak bisa melakukan assignment, karena tidak ada PIC yang tersedia", 404);
            
            foreach($workAreaAndProjectNo as $workAreaProject) {
                $pics = $this->getPicsByEntityAndProject($workAreaProject);
                $totalPics = count($pics);
                $preventives = $this->getPreventivesByEntityAndProject($workAreaProject);
                $arrayPecah = $this->arraySplit($preventives->toArray(), (int) $totalPics);
                
                if(count($preventives) > 0) {
                    // $i = 0;
                    foreach($pics as $i => $pic) {
                        $dataArrayIndex = json_decode (json_encode ($arrayPecah[$i]), FALSE);
                        if(!empty($dataArrayIndex)) {

                            foreach($dataArrayIndex as $index => $preventive) {
                                
                                $save = $this->preventiveMaintenace->where('id', $preventive->id)->update([
                                    "assign_to" => $pic->username,
                                    "assign_date" => $this->dateTime->format("Y-m-d"),
                                    "updated_at" => $this->dateTime->format("Y-m-d H:i:s"),
                                    "updated_by" => auth()->user() ? auth()->user()->tenant_code : '[System]',
                                ]);
                                
                                $dataPreventive = (array) $preventive;
                                unset($dataPreventive['created_at']);
                                unset($dataPreventive['created_by']);
                                unset($dataPreventive['updated_at']);
                                unset($dataPreventive['updated_by']);
                                (new PreventiveMaintenanceHistory())->insertLog($dataPreventive);

                                // cek ketersediaan check lists asset
                                $totalCheckLists = $this->pmTaskListAssetGroup->whereAssetGroupId($preventive->pm_asset_group_id)->count();
                                if($totalCheckLists > 0) {
                                    if(count($preventive->check_lists) == 0) {
                                        $this->insertDataTaskGroupAndTaskDetail($preventive);
                                    }
                                }
                            }

                        }
                        // $i++;
                    }
                }
            }
            DB::commit();
            $results = [
                "error" => false,
                "code" => 200,
                "header" => "Success",
                "message" => "Berhasil melakukan assignment"
            ];
        } catch(\Exception $err) {
            DB::rollBack();
            $results = [
                "error" => true,
                "code" => $err->getCode(),
                "header" => "Error",
                "message" => $err->getMessage()
            ];
        }
        return $results;
    }

    public function getWorkAreaAndProjectNo() {
        $data = DB::table("bms_work_area")->select("workArea", "project_no")
        ->whereDate('scanDate', $this->dateTime->format("Y-m-d"))
        ->where("workArea", auth()->user()->entity_project)
        ->whereNull('checkOutDate')->get();
        if(count($data) == 0) return [];

        $entitiesAndProjects = collect($data->toArray())->flatten()->unique(); // pluck
        $entitiesAndProjects = $entitiesAndProjects->toArray(); 
        return $entitiesAndProjects;
    }

    public function getPreventivesByEntityAndProject($workAreaProject) {
        return $this->preventiveMaintenace->with('check_lists', 'asset_group')
            ->whereEntityProject($workAreaProject->workArea)
            ->whereProjectCode($workAreaProject->project_no)->whereNull('actual_date')
            ->whereNull('assign_to')->whereNull('assign_date')->whereStatus('1')
            ->whereRaw("schedule_date <= CAST(GETDATE() AS DATE)")
            ->get()
        ;
    }

    public function getPicsByEntityAndProject($workAreaProject) {
        return DB::table("bms_work_area")->join("users", "bms_work_area.username", "=", "users.username")
            ->select("bms_work_area.username")
            ->where("bms_work_area.workArea", $workAreaProject->workArea)
            ->where("bms_work_area.project_no", $workAreaProject->project_no)
            ->where("users.emp_job_position", 'Engineer')
            ->whereDate('bms_work_area.scanDate', $this->dateTime->format("Y-m-d"))
            ->whereNull("bms_work_area.checkOutDate")
            ->whereRaw("Cast(DateDiff(hh, bms_work_area.scanDate, CURRENT_TIMESTAMP) as INT) < 8")
            ->get()
        ;
    }

    /**
     * this function for make array to multidimensional
     *
     * @param array $array
     * @param integer $pieces
     * @return void
     */
    public function arraySplit(array $array, int $pieces=2) 
    {   
        if ($pieces < 2) 
            return array($array); 
        
        $newCount = ceil(count($array)/$pieces);
        $a = array_slice($array, 0, $newCount); 
        $b = $this->arraySplit(array_slice($array, $newCount), $pieces-1); 

        // return array_merge_recursive(array($a),$b); 
        return array_merge(array($a), $b);
    }

    public function getCheckLists($preventive) {
        return $this->pmTaskListAssetGroup->with('check_standards')->whereAssetGroupId($preventive->pm_asset_group_id)
            ->whereRangeDay($preventive->asset_group->pm_schedule_time)
            ->whereStatus('active')->get()
        ;
    }

    public function insertDataTaskGroupAndTaskDetail($preventive) {
        // insert first rule check list
        $this->insertFirstRuleChecklist($preventive);

        // setelah insert first rule check list, ambil group range_day yg tdak sama dengen schedule time
        $groupRangeDay = PmTaskListAssetGroup::select('range_day')->where('asset_group_id', $preventive->asset_group->id)
        ->whereNotIn('range_day', [$preventive->asset_group->pm_schedule_time])->groupBy('range_day')->get();
        
        $pmScheduleAsset = DB::table("pm_schedule_assets")->where("pm_asset_detail_id", $preventive->pm_asset_detail_id)
        ->where("pm_schedule_date", $preventive->schedule_date)->first();
        
        foreach($groupRangeDay as $group_range_day)
        {   
            $lastDayPm = !is_null($pmScheduleAsset) && !is_null($pmScheduleAsset->pm_schedule_time) ? 
                $pmScheduleAsset->pm_schedule_time  : (int) $preventive->asset_detail->last_day_pm;

            if($lastDayPm != 0) {
                if($lastDayPm % $group_range_day->range_day == 0) {
                    $findChekList = PmTaskListAssetGroup::where("asset_group_id", $preventive->pm_asset_group_id)
                        ->where('range_day', $group_range_day->range_day)->whereStatus('active')->get()->toArray();
                    $this->insertCheckList($preventive->id, $preventive->check_lists);
                }
            }
            
        }
    }

    public function insertFirstRuleChecklist($preventive) {
        $checkLists = $this->getCheckLists($preventive);
        foreach($checkLists as $checkList) {
            $saveGroup = PreventiveMaintenanceGroup::create([
                "transaksi_preventive_maintenance_id" => (int) $preventive->id,
                "pm_task_list_asset_group_id" => $checkList->id,
                "created_by" => auth()->user() ? auth()->user()->tenant_code : "[System]",
                "updated_by" => auth()->user() ? auth()->user()->tenant_code : "[System]",
            ]);

            foreach($checkList->check_standards as $check_standard) {
                if($check_standard) {
                    PreventiveMaintenanceDetail::create([
                        "transaksi_preventive_maintenance_group_id" => $saveGroup->id,
                        "pm_task_list_asset_group_detail_id" => $check_standard->id,
                        "created_at" => $this->dateTime->format("Y-m-d H:i:s"),
                        "updated_at" => $this->dateTime->format("Y-m-d H:i:s"),
                        "created_by" => auth()->user() ? auth()->user()->tenant_code : "[System]",
                        "updated_by" => auth()->user() ? auth()->user()->tenant_code : "[System]",
                        "is_required" => (int) $check_standard->is_required,
                        "image_required" => (int) $check_standard->image_required,
                        "video_required" => (int) $check_standard->video_required,
                    ]);
                }
            }
        }
    }

    public function insertCheckList($preventiveId, $checklists)
    {
        foreach($checklists as $row)
        {
            $saveGroup = PreventiveMaintenanceGroup::create([
                "transaksi_preventive_maintenance_id" => (int) $preventiveId,
                "pm_task_list_asset_group_id" => $row->id,
                "created_by" => auth()->user() ? auth()->user()->username : "system",
                "updated_by" => auth()->user() ? auth()->user()->username : "system",
            ]);
            
            $details = PmTaskListGroupDetail::where('pm_task_list_asset_group_id', $row->id)
                ->whereStatus('active')->get()->toArray();

            foreach($details as $detail)
            {
                $saveDetail = PreventiveMaintenanceDetail::insert([
                    "transaksi_preventive_maintenance_group_id" => $saveGroup->id,
                    "pm_task_list_asset_group_detail_id" => $detail['id'],
                    "created_at" => $this->dateTime->format("Y-m-d H:i:s"),
                    "updated_at" => $this->dateTime->format("Y-m-d H:i:s"),
                    "created_by" => auth()->user() ? auth()->user()->username : "system",
                    "updated_by" => auth()->user() ? auth()->user()->username : "system",
                    "is_required" => (int) $detail['is_required'],
                    "image_required" => (int) $detail['image_required'],
                    "video_required" => (int) $detail['video_required'],
                ]);
            }
        }
    }
}