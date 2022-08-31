<?php

namespace App\Http\Controllers\invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use DataTables;
use Carbon;
use File;

class invoiceController extends Controller
{
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
    $user = $this->user();
    $tenant = $this->tenant();
    $filter_month = $this->getMonth();
    $filter_year = DB::select("select distinct year(sender_date) as year from invoice_receipt_hd where debtor_acct = '$user->tenant_code'");
    return view('invoice.history.index', [
      'tenant' => $tenant,
      'filter_year' => $filter_year,
      'filter_month' => $filter_month,
    ]);
  }

  public function getMonth()
  {
    $data = [];
    $id = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    $text = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    foreach($id as $key => $val){
      $data[] = [
        'id'  => $id[$key],
        'text'  => $text[$key],
      ];
    }
    return $data;
  }

  public function sqlIndex($month, $year)
  {
    $user = $this->user();
    $sql = "SELECT 
              ROW_NUMBER() OVER (ORDER BY a.receipt_no) AS id,
              a.receipt_no,
              FORMAT(a.sender_date, 'dd/MM/yyyy') sender_date,
              a.entity_project,
              a.project_no,
              b.debtor_acct,
              b.invoice_no,
              b.description,
              b.mbase_amt
            FROM invoice_receipt_hd a
            LEFT JOIN invoice_receipt_dtl b ON b.receipt_no = a.receipt_no
            WHERE 
            a.receipt_date IS NOT NULL AND
            a.entity_project = '$user->entity_project' AND 
            a.project_no = '$user->project_no' AND 
            b.debtor_acct = '$user->tenant_code' AND
            MONTH(a.sender_date) = '$month' AND
            YEAR(a.sender_date) = '$year'";

    return $sql;
  }

  public function listData()
  {
    $month = $_GET['month'];
    $year = $_GET['year'];

    $data = DB::select($this->sqlIndex($month, $year));

    return DataTables::of($data)
            ->addColumn('mbase_amt', function($data){
              return number_format(str_replace('.00', '', $data->mbase_amt), 2);
            })
            ->rawColumns(['mbase_amt'])
            ->make(true);
  }


  public function sqlDataModal($entity_project, $project_no, $debtor_acct, $receipt_no, $invoice_no)
  {
    $sql = "SELECT 
      b.invoice_no,
      b.description,
      a.receipt_no as delivery_no,
      a.sender_date as delivery_date,
      a.messanger_sender as delivery_name,
      a.receipt_date as receiver_date,
      a.receipt_name as receiver_name,
      b.due_date,
      REPLACE(b.mbase_amt, '.00', '') as invoice_amount,
      CONVERT(varchar(max), (
        SELECT sqla.mbal_amt FROM(
          SELECT aa.entity_cd + '01' as entity_project, * FROM DB_IFCA.mmp_live.mgr.ar_ledger aa WHERE aa.entity_cd in (SELECT entity_cd FROM DB_IFCA.mmp_live.mgr.cf_entity WHERE entity_cd NOT IN ('2201', '2101') ) 
          UNION ALL
          SELECT ab.entity_cd + '02' as entity_project, * FROM DB_IFCA.ilo_live.mgr.ar_ledger ab WHERE entity_cd = '3015'
        ) sqla
        WHERE
        sqla.entity_project = a.entity_project AND sqla.debtor_acct = b.debtor_acct AND sqla.doc_no = b.invoice_no
      ) ) as balance
    FROM invoice_receipt_hd a
    LEFT JOIN invoice_receipt_dtl b ON b.receipt_no = a.receipt_no
    WHERE 
    a.receipt_date IS NOT NULL AND 
    a.entity_project = '$entity_project' AND
    a.project_no = '$project_no' AND
    b.debtor_acct = '$debtor_acct' AND
    a.receipt_no = '$receipt_no' AND 
    b.invoice_no = '$invoice_no'
    ";

    return $sql;
  }

  public function getDataModal(Request $request)
  {
    $data = DB::select($this->sqlDataModal($request->entity_project, $request->project_no, $request->debtor_acct, $request->receipt_no, $request->invoice_no));

    $response['data'] = [
      'invoice_no' => $data[0]->invoice_no,
      'invoice_amount' => number_format($data[0]->invoice_amount, 2),
      'balance' => $data[0]->balance,
      'description' => $data[0]->description,
      'delivery_no' => $data[0]->delivery_no,
      'delivery_date' => Carbon::parse($data[0]->delivery_date)->format('d/m/Y'),
      'delivery_name' => $data[0]->delivery_name,
      'receiver_date' => Carbon::parse($data[0]->receiver_date)->format('d/m/Y'),
      'receiver_name' => $data[0]->receiver_name,
      'due_date' => Carbon::parse($data[0]->due_date)->format('d/m/Y'),
    ];

    $data_file = DB::table('invoice_receipt_file')->where('receipt_no', $request->receipt_no)->whereRaw("file_name like '%$request->invoice_no%'")->get();
    if(!empty($data_file)){
      // dd(file_exists(public_path("img/".$data_file[0]->file_name)));
      // dd(File::exists(asset("img/".$data_file[0]->file_name)));
      // dd(is_readable(asset("img/".$data_file[0]->file_name)));

      if(file_exists(public_path("img/".$data_file[0]->file_name) ) ){
        $response['file'] = [
          'file_name' => asset("img/".$data_file[0]->file_name),
        ];
      }else{
        $response['file'] = [
          'file_name' => asset("notfound.pdf"),
        ];
      }
    }else{
      $response['file'] = [
        'file_name' => asset("notfound.pdf"),
      ];
    }

    return response()->json($response);
  }
}
