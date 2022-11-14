<?php

namespace App\Http\Controllers\Preventives;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Preventives\Maintenance;
use App\Model\Preventives\PreventiveMaintenanceDetail;
use App\Model\Preventives\PreventiveMaintenanceGroup;
use Yajra\DataTables\DataTables;

class CheckListController extends Controller
{
    private $model, $maintenanceModel, $checkStandardModel;

    public function __construct(
        PreventiveMaintenanceGroup $model, Maintenance $maintenanceModel,
        PreventiveMaintenanceDetail $checkStandardModel
    )
    {
        $this->model = $model;
        $this->maintenanceModel = $maintenanceModel;
        $this->checkStandardModel = $checkStandardModel;
    }

    public function index($id) {
        $this->data['preventive'] = $this->maintenanceModel->find($id);
        return view("preventives.check_lists.index", $this->data);
    }

    public function dataTableCheckList($id, Request $request) {
        $data = DataTables::of($this->model->getDataByPreventiveId($id))
        ->rawColumns([])->make(true);
        return $data->content();
    }

    public function dataTableCheckStandardsByCheckListId($checkListId, Request $request) {
        $data = DataTables::of($this->checkStandardModel->getDataByCheckListId($checkListId))
        ->rawColumns([])->make(true);
        return $data->content();
    }
}
