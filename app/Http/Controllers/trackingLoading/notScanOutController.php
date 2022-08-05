<?php

namespace App\Http\Controllers\trackingLoading;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon;
use AppAction;
use Session;
use Validator;
use QrCode;
use Mpdf\Mpdf as MPDF;

use App\Model\trackingLoading\notScanOutModel;

class notScanOutController extends Controller
{
  public function query($dateSelected)
  {
    $date = explode(' - ', $dateSelected);
    $date1 = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
    $date2 = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
    // $date1 = $date[0];
    // $date2 = $date[1];
    $user = Auth::user();

    $data = DB::select("EXEC sp_tp_trackload_history 'notscanout', '$user->tenant_code', '$date1', '$date2'");
    return $data;
  }

  public function autonumber()
  {
    $autonumber = AppAction::autonumberback('bm_visit_track_ls_ticket', 'bak_no', '4', '/BAK/BPL/'.date('my'));
    return $autonumber;
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
    return view('tracking_loading.notscanout.index', [
      'tenant'  => $tenant,
    ]);
  }

  public function listHistory()
  {
    $data = $this->query($_GET['dateSelected']);

    return DataTables::of($data)
            ->addColumn('action', function($data){
              $check_bak = DB::table('bm_visit_track_ls_ticket')->where('id_visit_track', $data->id)->first();
              $html = '';
              $param = 'id='.$data->id.'/identifier='.$data->identifier;
              $param_encode = base64_encode($param);
              $html .= '
                <div style="text-align: center;">
              ';
              
              if(is_null($check_bak)){
                $html .= '
                  <a href="/tracking-loading/not-scan-out/request-bak/'.$param_encode.'" class="btn btn-sm btn-warning" title="Request BAK"><i class="fa fa-pencil" style="margin: 0px !important"></i></a>
                ';
              }else{
                $html .= '<a href="/tracking-loading/not-scan-out/print-pdf/?param='.$check_bak->bak_no.'" target="_blank" class="btn btn-sm btn-danger" title="Print BAK"><i class="fa fa-file-pdf-o" style="margin: 0px !important"></i></a>';
              }

              $html .= '
                </div>
              ';
              return $html;
            })
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
              'action',
              'img_capture',
              'img_ktp',
            ])
            ->make(true);
  }

  public function requestBak($param)
  {
    $param = explode('/', base64_decode($param));
    $id = explode('=', $param[0]);
    $identifier = explode('=', $param[1]);
    $autonumber = $this->autonumber();
    $user = $this->user();
    $entity = DB::table('ifca_cf_entity')->where('entity_project', $user->entity_project)->first();
    $project = DB::table('ifca_pl_project')->where('entity_project', $user->entity_project)->where('project_no', $user->project_no)->first();
    $debtor = DB::table('ifca_ar_debtor')->where('entity_project', $user->entity_project)->where('project_no', $user->project_no)->where('debtor_acct', $user->tenant_code)->first();

    $parse = [
      'autonumber' => $autonumber,
      'id' => $id,
      'identifier' => $identifier,
      'user' => $user,
      'entity' => $entity,
      'project' => $project,
      'debtor' => $debtor
    ];

    return view('tracking_loading.notscanout.create', $parse);
  }

  public function store(Request $request)
  {
    // dd(Session::get('userLogin'));
    $user = Session::get('userLogin');

    $validator = Validator::make($request->all(), [
      'id_visit_track' => 'required',
      'bak_no' => 'required',
      'entity_project' => 'required',
      'project_no' => 'required',
      'debtor_acct' => 'required',
      'identifier' => 'required',
      'police_no' => 'required',
      'identity_no' => 'required',
      'identity_name' => 'required',
    ]);

    if($validator->fails()){
      return response()->json([
        'status' => 'error',
        'message' => 'Error.. '.$validator->errors()->all()[0],
      ]);
    }

    $data = $validator->valid();
    $data['created_by'] = $user['email'];
    // dd($data);

    DB::beginTransaction();
    try{
      notScanOutModel::updateOrCreate(['bak_no' => $data['bak_no']], $data);
      DB::commit();

      return response()->json([
        'status' => 'success',
        'message' => 'Success.. Data has been submit.',
      ]);
    }catch(\Exception $e){
      DB::rollback();
      return response()->json([
        'status' => 'error',
        'message' => 'Error.. '.$e,
      ]);
    }
  }

  public function generate_bak() 
  {
    $param = $_GET['param'];
    $SQL = "SELECT 
            a.bak_no,
            a.identifier, 
            dbo.fnc_debtor_name(LEFT(a.entity_project,4), a.project_no, a.debtor_acct, RIGHT(a.entity_project,2) ) as debtor_name,
            dbo.fnc_entity_name(LEFT(a.entity_project,4), a.project_no, RIGHT(a.entity_project,2) ) as entity_name, 
            a.police_no, 
            a.identity_no, 
            a.identity_name 
            FROM bm_visit_track_ls_ticket a join bm_visit_track b
            ON a.identifier = b.identifier AND a.entity_project = b.entity_project
            WHERE bak_no = '$param'";
            
    $result = collect(DB::select($SQL))->first();
    $qrcode = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', QrCode::size(90)->generate($result->identifier));

    $content = '';
    $content .= '
    <style>
      body{
        font-size: 12px;
      }
      td{
        padding: 5px;
      }
    </style>
    <body>
    ';
    $content .= '<div style="width:100%; text-align:center;"><h3><b><u>Berita Acara Kehilangan</u></b></h3></div>';
    $content .= '<div style="width:100%; text-align:center;">'.$result->bak_no.'</div>';
    $content .= '<div style="width:100%; text-align:left; font-size:12px"><p><b>Berita Acara Kehilangan</b> 
                ini dibuat pada hari ini, '.$this->namebyDay(date('D')).' tanggal '.date('d F Y').' bertempat di PT. Bukit Properti Logistik
                 oleh saya yang menyatakan : </p></div>';
    $content .= '
    <div style="width:100%; text-align:left; font-size:12pt; margin-left:50px;">
      <table>
        <tr>
          <td>Perusahaan</td>
          <td>:</td>
          <td>'.strtoupper($result->debtor_name).'</td>
        </tr>
        <tr>
          <td>Nama</td>
          <td>:</td>
          <td>'.strtoupper(Session::get('userLogin')['name']).'</td>
        </tr>
      </table>
    </div>
    ';
    // $content .= '<p><div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">Perusahaan <span style="margin-left:30px;">'.strtoupper($result->debtor_name).'<span></span></div>';
    // $content .= '<div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">Nama <span style="margin-left:30px;">'.strtoupper($result->debtor_name).'<span></span></div>';
    // $content .= '<div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">Perusahaan <span style="margin-left:30px;">'.strtoupper($result->debtor_name).'<span></span></div>';
    $content .= '<div style="width:100%; text-align:left; margin-top:30px">Dengan Ini menyatakan bahwa pengemudi kami kehilangan karcis tiket masuk area Warehouse PT. Bukit Properti Logistik, adapun data identitas pengemudi dan kendaraan sebagai berikut :</div>';
    $content .= '
    <div style="width:100%; text-align:left; margin-left:50px;">
      <table>
        <tr>
          <td>No. Polisi</td>
          <td>:</td>
          <td>'.strtoupper($result->police_no).'</td>
        </tr>
        <tr>
          <td>No. Identitas Pengemudi</td>
          <td>:</td>
          <td>'.strtoupper($result->identity_no).'</td>
        </tr>
        <tr>
          <td>Nama Identitas Pengemudi</td>
          <td>:</td>
          <td>'.strtoupper($result->identity_name).'</td>
        </tr>
      </table>
    </div>
    ';
    // $content .= '<div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">No Polisi Kendaraan : <span style="margin-left:30px;">'.strtoupper($result->police_no).'<span></span></div>';
    // $content .= '<div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">No Identitas Pengemudi : <span style="margin-left:30px;">'.strtoupper($result->identity_no).'<span></span></div>';
    // $content .= '<div style="width:100%; text-align:left; font-size:12pt; margin-left:20px;">Nama Identitas Pengemudi : <span style="margin-left:30px;">'.strtoupper($result->identity_name).'<span></span></div></p>';

    $content .= '<table style="width:100%; margin-top: 100px">';
    $content .= '<tr>';
    $content .= '<td style="text-align: center;">'.$qrcode.'</td>';  
    $content .= '<td></td>';
    $content .= '<td style="text-align:center;">Bekasi, '.date('d F Y').'<br><br><br><br><br><br>('. strtoupper($result->debtor_name) .')</td>';  
    $content .= '</tr>';
    $content .= '</table>';
    $content .= '
    </body>
    ';


    // $content .= "</table>";

    // return $content;
    // print pdf

    $documentFileName = 'BAK '.$result->bak_no;
      $document = new MPDF( [
        'mode' => 'utf-8',
        'format' => 'A4',
        // 'orientation' => 'L',
        'margin_header' => '3',
        'margin_top' => '20',
        'margin_bottom' => '20',
        'margin_footer' => '2',
      ]);

      $header = [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="'.$documentFileName.'"'
      ];

      $document->setTitle($documentFileName);
      // $document->setFooter('{PAGENO}');

      $document->WriteHTML($content);

      // Save PDF on your public storage 
      // $document->debug = true;
      $document->Output($documentFileName, "I");

      // exit;
  }

  public function namebyDay()
  {
    $hari = date("D");
  
    switch($hari){
      case 'Sun':
        $hari_ini = "Minggu";
      break;
  
      case 'Mon':			
        $hari_ini = "Senin";
      break;
  
      case 'Tue':
        $hari_ini = "Selasa";
      break;
  
      case 'Wed':
        $hari_ini = "Rabu";
      break;
  
      case 'Thu':
        $hari_ini = "Kamis";
      break;
  
      case 'Fri':
        $hari_ini = "Jumat";
      break;
  
      case 'Sat':
        $hari_ini = "Sabtu";
      break;
      
      default:
        $hari_ini = "Tidak di ketahui";		
      break;
    }
  
    return "<b>" . $hari_ini . "</b>";
  }
}
