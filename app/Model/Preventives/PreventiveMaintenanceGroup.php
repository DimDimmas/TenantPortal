<?php

namespace App\Model\Preventives;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreventiveMaintenanceGroup extends Model
{
    public $table = 'transaksi_preventive_maintenance_groups';

    protected $fillable = [
        "transaksi_preventive_maintenance_id", "pm_task_list_asset_group_id",
        "status", "remark", "value", "created_at", "updated_at", "created_by", "updated_by",
    ];

    public function getDataByPreventiveId($id) {
        return DB::table("view_transaksi_preventive_maintenance_groups")->where('transaksi_preventive_maintenance_id', $id);
    }
}
