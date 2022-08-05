<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon;

//model
use App\Model\trackingLoading\bmVisitTrackModel;

//services
use App\Services\trackingLoading\pdf;
use App\Services\trackingLoading\excel;

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

  public function listHistory()
  {
    $data = $this->query($_GET['dateSelected']);

    return DataTables::of($data)
            ->addColumn('img_capture', function($data){
              $html = '';
              $html = '
                <img src="https://api.mmproperty.com/storage/bms_visit_track/capture/'.$data->image_capture.'" alt="'.$data->image_capture.'" width="200px">
              ';
              return $html;
            })
            ->addColumn('img_ktp', function($data){
              $html = '';
              $html = '
                <img src="https://api.mmproperty.com/storage/bms_visit_track/ktp/'.$data->ktp_attachment.'" alt="'.$data->ktp_attachment.'" width="200px">
              ';
              return $html;
            })
            ->rawColumns([
              'img_capture',
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
}
