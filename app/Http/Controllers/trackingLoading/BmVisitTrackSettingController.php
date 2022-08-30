<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\BmVisitTrackMstSizeType;
use App\Model\BmVisitTrackSetting;
use App\Services\BmVisitTrackSettingService;
use Illuminate\Support\Facades\Validator;

class BmVisitTrackSettingController extends Controller
{
    protected $bmVisitTrackSettingModel, $service;
    public function __construct(BmVisitTrackSetting $bmVisitTrackSetting, BmVisitTrackSettingService $service)
    {
        $this->bmVisitTrackSettingModel = $bmVisitTrackSetting;
        $this->service = $service;
    }

    public function index() {
        $this->data['tenant'] = $this->getTenantCompanyByCurrentUser();
        $this->data['types'] = (new BmVisitTrackSetting)->types();
        $this->data['statuses'] = (new BmVisitTrackSetting)->statuses();
        $this->data['sizeTypes'] = BmVisitTrackMstSizeType::select("id", "name")->where('debtor_acct', auth()->user()->tenant_code)->get();
        return view("tracking_loading.settings.index", $this->data);
    }

    public function getData(Request $request, BmVisitTrackSettingService $service) {
        $data = $this->service->getAllData($request);

        return response()->json($data, 200);
    }

    public function createOrUpdate(Request $request) {
        $results = [];
        try {
            $id = $request->id ? (int) $request->id : null;
            $rules = null;

            if($id) {
                $rules = [
                    'type' => 'required',
                    "bm_visit_track_mst_size_type_id" => "required",
                    'name' => 'required:unqiue.bm_visit_track_settings,name,'.$id,
                    'value' => 'required',
                    'status' => 'required',
                ];
            } else {
                $rules = [
                    'type' => 'required',
                    "bm_visit_track_mst_size_type_id" => "required",
                    'name' => 'required:unqiue.bm_visit_track_settings,name',
                    'value' => 'required',
                    'status' => 'required',
                ];
            }

            $validator = Validator::make($request->except("id", "_method", "_token"), $rules);

            if($validator->fails()) {
                $results['error'] = true;
                $results['code'] = '422';
                $results['message'] = "Validation error";
                $results['errors'] = $validator->errors();
                return response()->json($results, 200);
            }

            $data = $validator->valid();
            $data['name'] = trim(strtolower($data['name']));
            $data['description'] = trim(strtolower($data['description']));
            $data['debtor_acct'] = trim(auth()->user()->tenant_code);
            $data['entity_project'] = trim(auth()->user()->entity_project);
            $data['project_no'] = trim(auth()->user()->project_no);
            $proccess = $this->service->createOrUpdate($data, $id);
            return response()->json($proccess, 200);

        } catch (\Exception $e) {
            //throw $th;
            $results = [
                'error'     => false,
                'code'        => $e->getCode(),
                'message'     => $e->getMessage(),
                'errors'      => null
            ];
            return response()->json($results, 200);
        }
    }

    public function destroy($id, Request $request) {
        $results = [];
        try {
            $find = $this->bmVisitTrackSettingModel->find($id);

            if(!$find) throw new \Exception("Data not found", 404);

            if($find->status == 'active') throw new \Exception("Can't delete this data. Please inactive first.", 403);

            if($find->delete()) {
                $results = [
                    'error'     => false,
                    'code'        => 200,
                    'message'     => "Data has been deleted.",
                    'errors'      => null
                ];
            } else {
                throw new \Exception("Failed to deleted data.", 500);
            }
        } catch (\Exception $e) {
            //throw $th;
            $results = [
                'error'     => true,
                'code'        => $e->getCode(),
                'message'     => $e->getMessage(),
                'errors'      => null
            ];
        }
        return response()->json($results, 200);
    }
}
