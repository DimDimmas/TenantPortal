<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreventiveMaintenanceDetail extends Model
{
    public $table = 'transaksi_preventive_maintenance_details';

    public $fillable = [
        'transaksi_preventive_maintenance_group_id', 'pm_task_list_asset_group_detail_id', 'status',
        'remark', "value", 'image', "created_by", "updated_by", "is_required", "image_required", "video_required",
    ];

    public $timestamps = false;

    public function getDataByCheckListId($checkListId) {
        return DB::table('view_transaksi_preventive_maintenances_details')->where('transaksi_preventive_maintenance_group_id', $checkListId);
    }
    
}
