<?php

namespace App\Http\Controllers\Preventives;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ProccessRefreshCheckListPreventiveJob;
use App\Services\Preventives\MaintenancesService;
use App\Services\Preventives\ShareTaskService;

class MaintenanceController extends Controller
{
    private $maintenanceService;
    public function __construct(MaintenancesService $maintenanceService)
    {
        $this->maintenanceService = $maintenanceService;
    }

    public function index() {
        return view("preventives.maintenances.index");
    }

    public function datatable(Request $request) {
        $data = $this->maintenanceService->datatable($request);
        return $data->content();
    }

    public function shareTasks(ShareTaskService $service) {
        return response()->json($service->shareTaskToPersonInCharges());
    }

    public function refreshCheckListAll() {
        ini_set('max_execution_time', '9999999999999999999999');
        ProccessRefreshCheckListPreventiveJob::dispatch();
    }

    public function dataTableReschedule(Request $request) {
        $data = $this->maintenanceService->dataTableReschedule($request);
        return $data->content();
    }

    public function reschedule($id, Request $request) {
        $results = $this->maintenanceService->reschedule($id, $request);
        return response()->json($results, $results['code']);
    }

    public function changeAssignTo($id, Request $request) {
        $results = $this->maintenanceService->changeAssignTo($id, $request);
        return response()->json($results, $results['code']);
    }
}
