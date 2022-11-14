<?php
namespace App\Services\Preventives;

use App\Model\Preventives\Maintenance;
use App\Model\Preventives\PmTaskListAssetGroup;
use App\Model\Preventives\PreventiveMaintenanceDetail;
use App\Model\Preventives\PreventiveMaintenanceGroup;
use Illuminate\Support\Facades\DB;

class PreventiveRefreshChecklistService {
    private $preventiveModel, $checkListModel, $checkStandardModel,
        $pmTaskListAssetGroupModel, $shareTaskService
    ;

    public function __construct()
    {
        $this->preventiveModel = new Maintenance();
        $this->checkListModel = new PreventiveMaintenanceGroup();
        $this->checkStandardModel = new PreventiveMaintenanceDetail();
        $this->pmTaskListAssetGroupModel = new PmTaskListAssetGroup();
        $this->shareTaskService = new ShareTaskService($this->preventiveModel, $this->pmTaskListAssetGroupModel);
    }

    public function proccess() {
        $results = [];
        DB::beginTransaction();
        try {      
            // get data preventives where status is new = 1
            $preventivesStatusNew = $this->preventiveModel->getDataIsStatusNew();
            // proses pembersihan check list dan check standard
            $cleanProccess = $this->refreshChecklistAndCheckStandardPreventive($preventivesStatusNew);
            DB::commit();
            
        } catch (\Exception $err) {
            DB::rollBack();
            
        }
    }

    public function refreshChecklistAndCheckStandardPreventive($preventivesStatusNew)
    {
        foreach($preventivesStatusNew as $preventive) {
            
            if(!is_null($preventive->asset_group) && !is_null($preventive->asset_detail)) {
                if(!is_null($preventive->check_lists)) {

                    // pluck pm_task_list_asset_group_id
                    $checkListIds = $preventive->check_lists->pluck('id')->toArray();
                    
                    // delete check standards by check list ids
                    $deleteCheckStandarsByCheckListIds = $this->checkStandardModel->whereTransaksiPreventiveMaintenanceGroupId($checkListIds)->delete();

                    // delete check list by preventive id
                    $deleteCheckListsByPreventiveId = $this->checkListModel->whereTransaksiPreventiveMaintenanceId($preventive->id)->delete();
                    $this->shareTaskService->insertDataTaskGroupAndTaskDetail($preventive);
                }

            }

        }
        return true;
    }
}