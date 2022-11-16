<?php

namespace App\Model\Preventives;

use App\Model\Bms\BmsTenant;
use App\PmAssetGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PmScheduleAsset extends Model
{
    public $table = 'pm_schedule_assets';

    protected $fillable = [
        "id", "entity_project", "project_code", "pm_location_id", "tenant_id", "pm_asset_group_id",
        "pm_asset_id", "pm_asset_detail_id", "pm_schedule_date", "is_submit", "created_at", "created_by",
        "updated_at", "updated_by", "pm_schedule_time"
    ];

    public function getAllDataByEntityProjectAndAssetDetail($request) {
        return DB::table("view_pm_schedule_assets")->where("entity_project", $request->entity_project)
        ->where("project_code", $request->project_code)->where("pm_asset_detail_id", $request->asset_detail_id);
    }
}
