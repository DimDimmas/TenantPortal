<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;

class PmTaskListAssetGroup extends Model
{
    public $table = 'pm_task_list_asset_groups';

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
        "code", "asset_group_id", "name", "description", "created_at", "created_by",
        "updated_at", "updated_by", "notification_days", "range_day", "status"
    ];

    public function check_standards() {
        return $this->hasMany(PmTaskListGroupDetail::class, 'pm_task_list_asset_group_id');
    }
}
