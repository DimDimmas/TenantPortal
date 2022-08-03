<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\BmVisitTrackMstSizeType;
use App\Services\BmVisitTrackSettingSizeService;
use Illuminate\Support\Facades\Validator;

class BmVisitTrackSettingSizeTypeController extends Controller
{
    public function index() {
        $this->data['tenant'] = $this->getTenantCompanyByCurrentUser();
        return view("tracking_loading.settings.size_types.index", $this->data);
    }

    public function getData(Request $request, BmVisitTrackSettingSizeService $service) {
        $data = $service->getAllData($request);
        return response()->json($data, 200);
    }

    public function createOrUpdate(Request $request, BmVisitTrackSettingSizeService $service) {
        $results = [];
        try {
            $id = $request->id ? (int) $request->id : null;
            $rules = null;

            if($id) {
                $rules = [
                    'name' => 'required:unqiue.bm_visit_track_settings,name,debtor_acct,'.$id,
                ];
            } else {
                $rules = [
                    'name' => 'required:unqiue.bm_visit_track_settings,name,debtor_acct',
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
            $data['description'] = $data['description'] ? trim(strtolower($data['description'])) : null;
            $proccess = $service->createOrUpdate($data, $id);
            $results = $proccess;

        } catch (\Exception $e) {
            $results = [
                'error'     => true,
                'code'        => $e->getCode(),
                'message'     => $e->getMessage(),
                'errors'      => null
            ];
        }
        // $data = $service->createOrUpdate($request);
        return response()->json($results, 200);
    }

    public function destroy($id, Request $request, BmVisitTrackMstSizeType $model) {
        $results = [];
        try {
            $find = $model->find($id);
            if(!$find) throw new \Exception("Data not found", 404);
            $proccess = $find->delete();
            $results = $results = [
                'error'     => false,
                'code'        => 200,
                'message'     => "Data has been deleted.",
                'errors'      => null
            ];
        } catch (\Exception $e) {
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
