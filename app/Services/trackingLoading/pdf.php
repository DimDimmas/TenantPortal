<?php
  namespace App\Services\trackingLoading;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Carbon;
  use Mpdf\Mpdf as MPDF;

  use App\Model\trackingLoading\bmVisitTrackModel;

  class pdf{
    // public function query($dateSelected)
    // {
    //   $date = explode(' - ', $_GET['dateSelected']);
    //   $date1 = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
    //   $date2 = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
    //   $user = Auth::user();

    //   $data = DB::select("
    //     WITH difference_in_seconds AS (
    //       SELECT
    //         a.id,
    //         a.debtor_acct,
    //         a.identifier,
    //         a.image_capture,
    //         a.ktp_attachment,
    //         a.type,
    //         a.scan_in,
    //         a.scan_out,
    //         b.name as tenant_name,
    //         DATEDIFF(SECOND, scan_in, scan_out) AS seconds
    //       FROM bm_visit_track a, ifca_ar_debtor b
    //       WHERE b.debtor_acct = a.debtor_acct
    //     ),
    //     differences AS (
    //       SELECT
    //         id,
    //         debtor_acct,
    //         identifier,
    //         image_capture,
    //         ktp_attachment,
    //         type,
    //         tenant_name,
    //         scan_in,
    //         scan_out,
    //         seconds,
    //         (seconds % 60) AS seconds_part,
    //         (seconds % 3600) AS minutes_part,
    //         (seconds % 3600 * 24) AS hours_part
    //       FROM difference_in_seconds
    //     )
        
    //     SELECT
    //       id,
    //       debtor_acct,
    //       identifier,
    //       image_capture,
    //       ktp_attachment,
    //       type,
    //       tenant_name,
    //       FORMAT(scan_in, 'dd/MM/yyyy hh:mm:ss tt') as scan_in,
    //       FORMAT(scan_out, 'dd/MM/yyyy hh:mm:ss tt') as scan_out,
    //       CONCAT(
    //       FLOOR(seconds / 3600 / 24), ' days ',
    //       FLOOR(hours_part / 3600), ' hours ',
    //       FLOOR(minutes_part / 60), ' minutes ',
    //         seconds_part, ' seconds'
    //       ) AS difference
    //     FROM differences
    //     WHERE debtor_acct = '$user->tenant_code' and format(scan_in, 'yyyy-MM-dd') >= '$date1' and format(scan_in, 'yyyy-MM-dd') <= '$date2'
    //     ORDER BY id desc
    //   ");
    //   return $data;
    // }

    public function body($data, $dateSelected, $user, $tenant)
    {
      set_time_limit(0);
      // $data = $this->query($dateSelected);
      $data = $data;
      // dd($data);
      
      $html = '';
      $style = '';
      $row = '';
      foreach($data as $key => $data){
        $row .= '
          <tr>
            <td>'.++$key.'</td>
            <td><img src="http://127.0.0.1:8080/img/bms/photo/'.$data->image_capture.'" width="150px" alt="Image"></td>
            <td><img src="http://127.0.0.1:8080/img/bms/photo/'.$data->ktp_attachment.'" width="150px" alt="Image"></td>
            <td>'.$data->scan_in.'</td>
            <td>'.$data->scan_out.'</td>
            <td>'.$data->difference.'</td>
            <td>'.$data->type.'</td>
          </tr>
        ';
      }


      $style .= "
        <style>
          body{
            margin: 0px;
            padding: 0px;
            font-family: 'Arial';
            font-size: 12px;
          }
          .header{
            width: 100%;
            text-align: center;
          }
          .content{
            width: 100%;
            text-align: left;
          }
          .filter{
            width: 50%;
          }
          .table-content table{
            width: 100%;
          }
          .table-content table, .table-content th, .table-content td{
            padding: 5px 10px;
            border: 1px solid #000;
            border-collapse: collapse;
          }
        </style>
      ";

      $html .= '
        <html>
          '.$style.'
          <body>
            <div class="header">
              <h2>Report Tracking Loading</h2>
              <h3>'.$tenant->company_name.'</h3>
            </div>
            <div class="container" style="margin-top: 50px;">
              <div class="filter" style="margin-bottom: 20px;">
                <table>
                  <tr>
                    <td>Date</td>
                    <td>:</td>
                    <td>'.$dateSelected.'</td>
                  </tr>
                </table>
              </div>
              <div class="table-content">
                <table>
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Capture</th>
                      <th>KTP</th>
                      <th>Scan In</th>
                      <th>Scan Out</th>
                      <th>Duration</th>
                      <th>Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    '.$row.'
                  </tbody>
                </table>
              </div>
            </div>
          </body>
        </html>
      ';
      
      return $html;
    }

    public function print($data, $dateSelected, $user, $tenant)
    {
      $documentFileName = 'Report Tracking Loading - Date '.$dateSelected;
      $document = new MPDF( [
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'L',
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
      $document->setFooter('{PAGENO}');

      $document->WriteHTML($this->body($data, $dateSelected, $user, $tenant));

      // Save PDF on your public storage 
      $document->debug = true;
      $document->Output($documentFileName, "I");

      exit;
    }
  }