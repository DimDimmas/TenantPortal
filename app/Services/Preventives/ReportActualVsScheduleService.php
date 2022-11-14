<?php
namespace App\Services\Preventives;

use App\Model\Preventives\PmScheduleAsset;
use App\Model\Preventives\PmTaskListAssetGroup;
use Illuminate\Support\Facades\DB;

class ReportActualVsScheduleService {
    private $serviceReportFixSchedule;

    public function __construct(ReportProjectPreventiveService $serviceReportFixSchedule)
    {
        $this->serviceReportFixSchedule = $serviceReportFixSchedule;
    }

    public function generateData($request)
    {   
        $inputDate = explode("/", $request->date);
        $inputDate = $inputDate[1] . "-" . $inputDate[0] . "-01";
        $date1 = date("Y-m-01", strtotime($inputDate));
        $datePlus1Month = date("Y-m-t", strtotime("+1 month", strtotime($inputDate)));
        $month1 = date("m", strtotime($request->date));
        $year1 = date("Y", strtotime($request->date));
        $month2 = date("m", strtotime($datePlus1Month));
        $year2 = date("Y", strtotime($datePlus1Month));
        $totalDays1 = cal_days_in_month(CAL_GREGORIAN, (int) date("m", strtotime($date1)), (int) date("Y", strtotime($date1)));
        $totalDays2 = cal_days_in_month(CAL_GREGORIAN, (int) date("m", strtotime($datePlus1Month)), (int) date("Y", strtotime($datePlus1Month)));
        $newData = [];
        $total_days = [$totalDays1, $totalDays2];
        $months = [$month1, $month2];
        $years = [$year1, $year2];
        $ket = [date("F Y", strtotime($date1)), date("F Y", strtotime($datePlus1Month))];
        
        $data = DB::table("view_pm_schedule_assets AS a")->selectRaw("
            entity_project, entity_name, project_code, project,
            location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name,
            STUFF((
                SELECT ';' + CONVERT(VARCHAR, b.pm_schedule_date)
                FROM pm_schedule_assets b
                WHERE b.pm_asset_detail_id = a.pm_asset_detail_id 
                AND (b.pm_schedule_date BETWEEN '$date1' AND '$datePlus1Month')
                ORDER BY b.pm_schedule_date ASC
                FOR XML PATH('')
            ), 1, 1, '') AS schedule_dates,
            STUFF((
                SELECT ';' + CONVERT(VARCHAR, CAST(b.actual_date AS DATE))
                FROM transaksi_preventive_maintenances b
                WHERE b.pm_asset_detail_id = a.pm_asset_detail_id 
                AND (b.actual_date BETWEEN '$date1' AND '$datePlus1Month')
                ORDER BY b.actual_date ASC
                FOR XML PATH('')
            ), 1, 1, '') AS actual_dates
        ")
        // ->where('tenant_id', auth()->user()->tenant_id)
        ->groupBy("entity_project", "entity_name", "project_code", "project", "location_name", "tenant_person", "pm_asset_group_id", "pm_asset_detail_id", "barcode", "asset_name");

        if($request->date) {
            $data = $data->whereRaw("pm_schedule_date BETWEEN '$date1' AND '$datePlus1Month'");
        }

        if($request->keyword) {
            $data = $data->where("project", "LIKE", "%$request->keyword%")
            ->orWhere("entity_name", "LIKE", "%$request->keyword%")
            ->orWhere("tenant_person", "LIKE", "%$request->keyword%")
            ->orWhere("asset_name", "LIKE", "%$request->keyword%");
        }

        if($request->entity_project) {
            $data = $data->whereRaw("entity_project = '$request->entity_project'");
        }
        
        $data = $data->get();
        
        $firstSchedule = null;
        foreach($data as $index => $row) {
            $temp = [
                "entity_name" => $row->entity_name,
                "project_name" => $row->project,
                "location_name" => $row->location_name,
                "tenant_name" => $row->tenant_person ?? '',
                "barcode" => $row->barcode,
                "asset_name" => $row->asset_name,
            ];
            
            $firstSchedule = PmScheduleAsset::where("pm_asset_detail_id", $row->pm_asset_detail_id)->orderBy('pm_schedule_date', 'ASC')->first();
            $firstSchedule = $firstSchedule->pm_schedule_date;
            $checkListGroup = PmTaskListAssetGroup::select("range_day")->where("asset_group_id", $row->pm_asset_group_id)->get()->pluck('range_day')->toArray();
            $checkListGroup = array_unique($checkListGroup);
            $minRangeDay = count($checkListGroup) > 0 ? min($checkListGroup) : 7;
            $schedule_dates = $row->schedule_dates ? explode(";", $row->schedule_dates) : [];
            $actual_dates = $row->actual_dates ? explode(";", $row->actual_dates) : [];
            $temp['actual_dates'] = $actual_dates;
            $temp['schedule_dates'] = $schedule_dates;
            $tempDays = [];

            for($a = 0; $a < count($total_days); $a++) {
                $tempDays2 = [];
                for($i = 1; $i <= $total_days[$a]; $i++) {
                    $tempDays2["{$i}"] = null;
                    
                    for($k = 0; $k < count($schedule_dates); $k++) {

                        if($months[$a] == date("m", strtotime($schedule_dates[$k]))) {
                            if($i == (int) date("j", strtotime($schedule_dates[$k]))) {
                                $rangeDays = $this->serviceReportFixSchedule->getRangeDaysBetweenTwoDates($firstSchedule, $schedule_dates[$k]);
                                
                                $rangeDayFix = (intval(floor($rangeDays/$minRangeDay))) * $minRangeDay;
            
                                $tempDays2["{$i}"] = $this->serviceReportFixSchedule->decisionMonth($rangeDayFix);
                                break;
                            }
                        }
                        
                    }
                    if(is_null($tempDays2["{$i}"])) $tempDays2["{$i}"] = "";
                }
                $tempDays[] = $tempDays2;

            }
            $temp["month_dates"] = $tempDays;
            array_push($newData, $temp);
        }
        
        return [
            "data" => $newData,
            "total_days" => $total_days,
            "descs" => $ket,
            "months" => $months,
        ];
    }

    public function convertToHtmlTable($data, $months) {
        $html = "";
        
        foreach($data as $key => $row) {
            $html .= "<tr>";

            $html .= '
                <!-- <td>'. $row['project_name'] .'</td>
                <td>'. $row['tenant_name'] .'</td> -->
                <td>'. $row['asset_name'] .'</td>
            ';

            foreach($row['month_dates'] as $index => $month) {//dump($months[$index]);
                // foreach($)
                foreach($month as $k => $value) {
                    $td = '<td>'. $value .'</td>';
                    
                    foreach($row['actual_dates'] as $actual_date) {
                        if($months[$index] == date("m", strtotime($actual_date))) {
                            if($k == date('d', strtotime($actual_date))) {
                                $d = DB::table("view_transaksi_preventive_maintenances")->where('asset_name', $row['asset_name'])
                                ->whereRaw("CAST(actual_date AS DATE) = '". $actual_date ."'")->first();

                                $b = $value == '' ? '' : $value . '<hr style="border: 2px solid white !important" />';

                                if(strtotime($actual_date) > strtotime($d->schedule_date)) {
                                    $td = '<td style="background-color: #d6020d !important;">
                                        '. $b .'
                                        <h6 style="font-weight:bolder !important;color:white;">
                                            A
                                        </h6>
                                        <!-- <hr style="border: 2px solid white !important" /> -->
                                        <h6 style="font-weight:bolder !important;color:white;">Schedule Date<h6 />
                                        <h6 style="font-weight:bolder !important;color:white;">'. date("d/m/Y", strtotime($d->schedule_date)) .'<h6 />
                                    </td>';
                                    break;
                                } else {
                                    $td = '<td style="background-color: #ff03ea !important;">
                                        '. $b .'
                                        <h6 style="font-weight:bolder !important;color:white;">A</h6>
                                        <!-- <hr style="border: 2px solid white !important" /> -->
                                        <h6 style="font-weight:bolder !important;color:white;">Schedule Date<h6 />
                                        <h6 style="font-weight:bolder !important;color:white;">'. date("d/m/Y", strtotime($d->schedule_date)) .'<h6 />
                                    </td>';
                                    break;
                                }

                            }
                        }
                    }

                    $html .= $td;
                }
            }
            
            $html .= "</tr>";
        }
        return $html;
    }
}