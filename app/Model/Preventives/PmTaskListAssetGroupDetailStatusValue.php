<?php

namespace App\Model\Preventives;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class PmTaskListAssetGroupDetailStatusValue extends Model
{
    public $table = 'pm_task_list_asset_group_detail_status_values';

    public $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        "pm_task_list_asset_group_detail_id", "bms_status_id", "value",
        "created_at", "created_by", "updated_at", "updated_by", "status_name"
    ];

    protected $cast = [
        "value" => "int",
    ];

    public function task_list_detail()
    {
        return $this->belongsTo(PmTaskListGroupDetail::class, 'pm_task_list_asset_group_detail_id');
    }

}
