<?php
namespace App\Services\Preventives;

use App\Model\Preventives\PmScheduleAsset;
use App\Model\Preventives\PmTaskListAssetGroup;
use Illuminate\Support\Facades\DB;

class ReportProjectPreventiveService {
    public function generateData1($request)
    {
        $month = date("m", strtotime($request->date));
        $year = date("Y", strtotime($request->date));
        $totalDays = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
        $newData = [];
        $where = '';

        $data = DB::table("view_pm_schedule_assets a")->selectRaw("
            entity_project, entity_name, project_code, project,
            location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name,
            STUFF((
                SELECT ';' + CONVERT(VARCHAR, b.pm_schedule_date)
                FROM pm_schedule_assets b
                WHERE b.pm_asset_detail_id = a.pm_asset_detail_id 
                AND MONTH(pm_schedule_date) = '$month' AND YEAR(pm_schedule_date) = '$year'
                ORDER BY b.pm_schedule_date ASC
                FOR XML PATH('')
            ), 1, 1, '') AS schedule_dates
        ")->groupBy(DB::raw("entity_project, entity_name, project_code, project,
        location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name"));
        dd($data->toSql());
        if($request->date) {
            $where .= "
                WHERE MONTH(pm_schedule_date) = '$month' AND YEAR(pm_schedule_date) = '$year'
            ";
        }
        
        $data = new PmScheduleAsset;

        if($request->entity_project) {
            $where .= "
                AND entity_project = '{$request->entity_project}'
            ";
        }
        
        $data = DB::select("
            SELECT entity_project, entity_name, project_code, project,
            location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name,
            STUFF((
                SELECT ';' + CONVERT(VARCHAR, b.pm_schedule_date)
                FROM pm_schedule_assets b
                WHERE b.pm_asset_detail_id = a.pm_asset_detail_id 
                AND MONTH(pm_schedule_date) = '$month' AND YEAR(pm_schedule_date) = '$year'
                ORDER BY b.pm_schedule_date ASC
                FOR XML PATH('')
            ), 1, 1, '') AS schedule_dates
            FROM view_pm_schedule_assets a
            $where
            GROUP BY entity_project, entity_name, project_code, project,
            location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name
        ");
        
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
            $minRangeDay = min($checkListGroup);
            $schedule_dates = explode(";", $row->schedule_dates);

            for($i = 1; $i <= $totalDays; $i++) {
                $temp["{$i}"] = null;
                
                for($k = 0; $k < count($schedule_dates); $k++) {
                    if($i == (int) date("j", strtotime($schedule_dates[$k]))) {
                        $rangeDays = $this->getRangeDaysBetweenTwoDates($firstSchedule, $schedule_dates[$k]);
                        
                        $rangeDayFix = (intval(floor($rangeDays/$minRangeDay))) * $minRangeDay;
    
                        $temp["{$i}"] = $this->decisionMonth($rangeDayFix);
                        break;
                    }
                }
                if(is_null($temp["{$i}"])) $temp["{$i}"] = "";
            }

            array_push($newData, $temp);
        }
        
        return [
            "data" => $newData,
            "total_days" => $totalDays
        ];
    }

    public function generateData($request)
    {
        $month = date("m", strtotime($request->date));
        $year = date("Y", strtotime($request->date));
        $totalDays = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
        $newData = [];
        $where = '';
        
        $data = DB::table("view_pm_schedule_assets AS a")->selectRaw("
            entity_project, entity_name, project_code, project,
            location_name, tenant_person, pm_asset_group_id, pm_asset_detail_id, barcode, asset_name,
            STUFF((
                SELECT ';' + CONVERT(VARCHAR, b.pm_schedule_date)
                FROM pm_schedule_assets b
                WHERE b.pm_asset_detail_id = a.pm_asset_detail_id 
                AND MONTH(pm_schedule_date) = '$month' AND YEAR(pm_schedule_date) = '$year'
                ORDER BY b.pm_schedule_date ASC
                FOR XML PATH('')
            ), 1, 1, '') AS schedule_dates
        ")->groupBy("entity_project", "entity_name", "project_code", "project", "location_name", "tenant_person", "pm_asset_group_id", "pm_asset_detail_id", "barcode", "asset_name");
        


        if($request->date) {
            $data = $data->whereRaw("MONTH(pm_schedule_date) = '$month'")->whereRaw("YEAR(pm_schedule_date) = '$year'");
        }

        if($request->keyword) {
            $data = $data->where("project", "LIKE", "%$request->keyword%")->orWhere("tenant_person", "LIKE", "%$request->keyword%")->orWhere("asset_name", "LIKE", "%$request->keyword%");
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
            $schedule_dates = explode(";", $row->schedule_dates);

            for($i = 1; $i <= $totalDays; $i++) {
                $temp["{$i}"] = null;
                
                for($k = 0; $k < count($schedule_dates); $k++) {
                    if($i == (int) date("j", strtotime($schedule_dates[$k]))) {
                        $rangeDays = $this->getRangeDaysBetweenTwoDates($firstSchedule, $schedule_dates[$k]);
                        
                        $rangeDayFix = (intval(floor($rangeDays/$minRangeDay))) * $minRangeDay;
    
                        $temp["{$i}"] = $this->decisionMonth($rangeDayFix);
                        break;
                    }
                }
                if(is_null($temp["{$i}"])) $temp["{$i}"] = "";
            }

            array_push($newData, $temp);
        }
        
        return [
            "data" => $newData,
            "total_days" => $totalDays
        ];
    }

    public function getRangeDaysBetweenTwoDates($date1, $date2) {
        $oDate1=date_create(date("Y-m-d", strtotime($date1)));
        $oDate2=date_create(date("Y-m-d", strtotime($date2)));
        $diff=date_diff($oDate1,$oDate2);
        return $diff->format("%a");
    }

    public function decisionMonth($rangeDays) {
        $text = '';
        switch($rangeDays) {
            case $rangeDays % 365 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>12M</h6>";
                break;
            case $rangeDays % 360 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>12M</h6>";
                break;
            case $rangeDays % 348 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>12M</h6>";
                break;
            case $rangeDays % 336 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>12M</h6>";
                break;
            case $rangeDays % 186 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>6M</h6>";
                break;
            case $rangeDays % 180 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>6M</h6>";
                break;
            case $rangeDays % 174 == 0:
                $text =  "<h6 style='font-weight:bolder !important;color:#f29202;'>6M</h6>";
                break;
            case $rangeDays % 168 == 0:
                $text =  '<h6 style="font-weight:bolder !important;color:#f29202;">6M</h6>';
                break;
            case $rangeDays % 93 == 0:
                $text =  '<h6 style="font-weight:bolder !important;color:#f29202;">3M</h6>';
                break;
            case $rangeDays % 90 == 0:
                $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">3M</h6>';
                break;
            case $rangeDays % 87 == 0:
                $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">3M</h6>';
                break;
            case $rangeDays % 84 == 0:
                $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">3M</h6>';
                break;
            // case $rangeDays % 31 == 0 :
            //     $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">B</h6>';
            //     break;
            // case $rangeDays % 30 == 0 :
            //     $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">B</h6>';
            //     break;
            // case $rangeDays % 29 == 0 :
            //     $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">B</h6>';
            //     break;
            case $rangeDays % 28 == 0 :
                $text =  '<h6  style="font-weight:bolder !important;color:#f29202;">M</h6>';
                break;
            // case $rangeDays % 14 == 0 :
            //     $text =  '2M';
            //     break;
            case $rangeDays % 7 == 0 :
                $text =  '<h6 style="font-weight:bolder !important;color:#0345fc;">W</h6>';
                break;
            case $rangeDays % 1 == 0 :
                $text =  '<h6 style="font-weight:bolder !important;color:#03fc39;">D</h6>';
                break;
            default:
                $text = '';
        }

        return $text;
    }
}