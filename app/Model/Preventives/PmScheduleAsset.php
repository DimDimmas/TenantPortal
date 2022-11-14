<?php

namespace App\Model\Preventives;

use App\Model\Bms\BmsTenant;
use App\PmAssetGroup;
use Illuminate\Database\Eloquent\Model;

class PmScheduleAsset extends Model
{
    public $table = 'pm_schedule_assets';

    protected $fillable = [
        "id", "entity_project", "project_code", "pm_location_id", "tenant_id", "pm_asset_group_id",
        "pm_asset_id", "pm_asset_detail_id", "pm_schedule_date", "is_submit", "created_at", "created_by",
        "updated_at", "updated_by", "pm_schedule_time"
    ];
}
