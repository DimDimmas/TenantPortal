<?php

namespace App\Model\Preventives;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreventiveMaintenanceHistory extends Model
{
    public $table = 'transaksi_preventive_maintenance_histories';

    public $timestamps = false;

    public $fillable = [
        'trans_code', 'entity_project', 'project_code', 'location_id', 'assign_to',
        'pm_asset_group_id', 'pm_asset_id', 'pm_asset_detail_id', 'schedule_date',
        'actual_date', 'due_date', 'status', 'remark', 'created_at', 'updated_at', 'assign_date', 'total_value',
        "created_by", "updated_by", "corrective_ticket", "tenant_id"
    ];


    public function insertLog(array $preventive) {
        $preventive['created_at'] = date("Y-m-d H:i:s");
        $preventive['created_by'] = auth()->user() ? auth()->user()->tenant_code : '[System]';
        $preventive['updated_at'] = date("Y-m-d H:i:s");
        $preventive['updated_by'] = auth()->user() ? auth()->user()->tenant_code : '[System]';
        return $this->create($preventive);
    }
}
