<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\BmVisitTrack;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon;

//model
use App\Model\trackingLoading\bmVisitTrackModel;

//services
use App\Services\trackingLoading\pdf;
use App\Services\trackingLoading\excel;
use Illuminate\Support\Facades\Validator;

class historyController extends Controller
{
  public function query($dateSelected)
  {
    $date = explode(' - ', $dateSelected);
    $date1 = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
    $date2 = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
    // $date1 = $date[0];
    // $date2 = $date[1];
    $user = Auth::user();

    $data = DB::select("EXEC sp_tp_trackload_history 'history', '$user->tenant_code', '$date1', '$date2'");
    return $data;
  }

  protected function user()
  {
    $user = Auth::user();
    return $user;
  }

  protected function tenant()
  {
    $user = $this->user();
    // $data = DB::table('ifca_ar_debtor')->where('debtor_acct', $user->tenant_code)->first();
    $data = DB::table('bms_tenant_company')->where('entity_project', $user->entity_project)->where('project_no', $user->project_no)->where('tenant_code', $user->tenant_code)->first();
    return $data;
  }

  public function index()
  {
    $tenant = $this->tenant();
    return view('tracking_loading.history.index', [
      'tenant'  => $tenant,
    ]);
  }

  public function listHistory(Request $request)
  {
    $dateSelected = explode(" - ", $request->dateSelected);
    $awal = $dateSelected[0];
    $akhir = $dateSelected[1];

    $data = DB::table("view_bm_visit_track")
      ->selectRaw("
        id, identifier, entity_project, entity_name, project_no, project_name, debtor_acct, debtor_name, image_capture,
        ktp_attachment, scan_in, scan_out, type, plate_area, police_no, identity_no, identity_name,
        (
				  select dbo.fnc_getDateDifference(scan_in, scan_out) as datediff
				) AS difference
      ")
      ->where("debtor_acct", auth()->user()->tenant_code)
      ->whereRaw("FORMAT(scan_in, 'dd/MM/yyyy') BETWEEN '$awal' AND '$akhir' ");

    // $data = $this->query($_GET['dateSelected']);

    return DataTables::of($data)
            ->addColumn('img_capture', function($data){
              $html = '';
              $html = '
                <img src="https://api.mmproperty.com/storage/bms_visit_track/capture/'.$data->image_capture.'"
                  alt="'.$data->image_capture.'" class="img-thumbnail img-fluid"
                  onclick="showImage(this)"
                >
              ';
              return $html;
            })
            ->addColumn('img_ktp', function($data){
              $html = '';
              $html = '
                <img src="https://api.mmproperty.com/storage/bms_visit_track/ktp/'.$data->ktp_attachment.'" 
                  alt="'.$data->ktp_attachment.'" class="img-thumbnail img-fluid"
                  onclick="showImage(this)"  
                >
              ';
              return $html;
            })
            ->editColumn('scan_in', function($data) {
              return $data->scan_in ? Carbon::parse($data->scan_in)->format('d/m/Y H:i:s') : '';
            })
            ->editColumn('scan_out', function($data) {
              return $data->scan_out ? Carbon::parse($data->scan_out)->format('d/m/Y H:i:s') : '';
            })
            ->rawColumns([
              'img_capture', 'scan_in', 'scan_out',
              'img_ktp',
            ])
            ->make(true);
  }

  public function printPdf()
  {
    $pdf = new pdf;
    $data = $this->query($_GET['dateSelected']);
    return $pdf->print($data, $_GET['dateSelected'], $this->user(), $this->tenant());
  }

  public function printExcel()
  {
    $excel = new excel;
    $data = $this->query($_GET['dateSelected']);
    return $excel->print($data, $_GET['dateSelected'], $this->user(), $this->tenant());
  }

  public function createOrUpdate(Request $request, BmVisitTrack $bmVisitTrack) {
    $results = [];
    DB::beginTransaction();
    try {
        $validation = Validator::make($request->all(), [
            'entity_project' => 'required',
            'project_no' => 'required',
            'debtor_acct' => 'required',
        ]);
        
        if($validation->fails()) throw new \Exception($validation->errors(), 422);

        $data = $validation->valid();
        $id = $data['id'] ? $data['id'] : null;
        unset($data['id']);
        unset($data['_token']);
        unset($data['_method']);
        
        if(!is_null($id)) { // update
            $find = $bmVisitTrack->find($id);
            if(is_null($find)) throw new \Exception("Data not found", 404);
            
            $proccess = $find->update($data);
        } else { // create
            $proccess = $bmVisitTrack->create($data);
        }
        
        if(!$proccess) throw new \Exception("Failed to save data", 500);

        $results = [
            "error" => false,
            "header", "Success",
            "code" => 200,
            "message" => "Data has been saved",
            "errors" => null
        ];

        DB::commit();
    } catch(\Exception $err) {
        DB::rollBack();
        if($err->getCode() == 422) {
            $results = [
                "error" => true,
                "header", "Error",
                "code" => $err->getCode(),
                "message" => "Error Validation",
                "errors" => $err->getMessage()
            ];
        } else {
            $results = [
                "error" => true,
                "header", "Error",
                "code" => $err->getCode(),
                "message" => $err->getMessage(),
                "errors" => null
            ];
        }
    }
    return response()->json($results, 200);
}
}
