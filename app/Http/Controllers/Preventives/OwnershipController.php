<?php

namespace App\Http\Controllers\Preventives;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Preventives\Ownership;
use App\Model\Preventives\PmScheduleAsset;
use DateTime;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OwnershipController extends Controller
{
    public function index() {
        return view("preventives.ownerships.index");
    }

    public function datatables(Request $request, Ownership $ownership, DataTables $dataTables) {
        $data = $dataTables->query($ownership->getAllData())
        ->rawColumns([])->make(true);
        return $data->content();
    }

    public function changeScheduleDate(Request $request, PmScheduleAsset $model) {  
        $results = [];      
        DB::beginTransaction();
        try {
            $data = $request->except("_method", "_token");

            $schedule = $model->find($data['id']);

            if(is_null($schedule)) {
                return response()->json($this->responseMessage(true, 404, "Error", "Can not update schedule date, because data not found."), 200);
            }

            unset($data['id']);
            $data = array_merge($data, ["updated_at" => new DateTime(), "updated_by" => auth()->user()->tenant_code]);
            $updateSchedule = $schedule->update($data);
            DB::commit();
            $results = [
                "error" => false,
                "header" => "Success",
                "code" => 200,
                "message" => "Data berhasil disimpan",
            ];
        } catch(\Exception $err) {
            DB::rollBack();
            $results = [
                "error" => true,
                "header" => "Error",
                "code" => $err->getCode(),
                "message" => $err->getMessage(),
            ];
        }
        return response()->json($results, $results['code']);
    }

    public function datatablesSchedules(Request $request, PmScheduleAsset $model, DataTables $dataTables) {
        $data = $dataTables->query($model->getAllDataByEntityProjectAndAssetDetail($request))
        ->editColumn("is_submit", function($data) {
            $icon = $data->is_submit == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>';
            return $icon;
        })
        ->rawColumns(["is_submit"])->make(true);
        return $data->content();
    }
}
