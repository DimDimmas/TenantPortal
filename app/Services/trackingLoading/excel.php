<?php
  namespace App\Services\trackingLoading;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Carbon;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

  class excel
  {
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

    public function print($data, $dateSelected, $user, $tenant)
    {
      // $data = $this->query($dateSelected);
      $data = $data;

      $styling = array(
        'all' => array(
          'borders' => array(
            'allBorders' => array(
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => array('argb' => '9999999'),
            ),
          ),
        ),
        'top' => array(
          'borders' => array(
            'allBorders' => array(
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => array('argb' => '9999999'),
            ),
            'bottom' => array(
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
              'color' => array('argb' => '9999999'),
            ),
          ),
        )
      );
  
      $spreadsheet = new Spreadsheet();
      $sheet =  $spreadsheet->getActiveSheet();

      $sheet->setCellValue('A1', 'REPORT TRACKING LOADING '.$dateSelected);
      $sheet->setCellValue('A2', $tenant->name);

      $sheet->setCellValue('A4', 'No');
      $sheet->setCellValue('B4', 'Type');
      $sheet->setCellValue('C4', 'Scan In');
      $sheet->setCellValue('D4', 'Scan Out');
      $sheet->setCellValue('E4', 'Duration');

      $no = 1;
      $rows = 5;
      foreach($data as $key => $data){
        $sheet->setCellValue('A'.$rows, $no);
        $sheet->setCellValue('B'.$rows, $data->type);
        $sheet->setCellValue('C'.$rows, $data->scan_in);
        $sheet->setCellValue('D'.$rows, $data->scan_out);
        $sheet->setCellValue('E'.$rows, $data->difference);
        $no++;
        $rows++;
      }

      $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
      $sheet->getStyle("A4:E4")->getFont()->setBold(true);
      $sheet->getStyle("A4:E4")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('1dd1a1');
      $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(14);
      $sheet->getStyle("A2")->getFont()->setBold(true)->setSize(14);
      $sheet->mergeCells('A1:E1');
      $sheet->mergeCells('A2:E2');
      $sheet->mergeCells('A3:E3');
      $sheet->getStyle("A1:E".$rows)->applyFromArray($styling['all']);
      $sheet->getColumnDimension('A')->setAutoSize(true);
      $sheet->getColumnDimension('B')->setAutoSize(true);
      $sheet->getColumnDimension('C')->setAutoSize(true);
      $sheet->getColumnDimension('D')->setAutoSize(true);
      $sheet->getColumnDimension('E')->setAutoSize(true);
      $sheet->setShowGridlines(false);

      $fileName = 'Report-Tracking-Loading.xlsx';
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
      $writer->save('php://output'); 
    }
  }