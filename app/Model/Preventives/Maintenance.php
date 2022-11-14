<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Maintenance extends Model
{
    public $table = 'transaksi_preventive_maintenances';

    protected $fillable = [
        "id", "trans_code", "entity_project", "project_code", "location_id",
        "pm_asset_group_id", "pm_asset_id", "pm_asset_detail_id", "tenant_id",
        "assign_to", "assign_date", "schedule_date", "actual_date", "due_date",
        "status", "remark", "total_value", "corrective_ticket", "created_at",
        "created_by", "updated_at", "updated_by"
    ];

    public function getDataTable($request) {
        $data  = DB::table("view_transaksi_preventive_maintenances")->whereNotIn('status', ['6', '20']);

        // cek kondisi lazada
        $userEntity = trim(auth()->user()->entity_project) ?? null;
        $userProject  = trim(auth()->user()->project_no) ?? null;
        $userTenant  = trim(auth()->user()->tenant_id) ?? null;
        $data = $data
            ->where('entity_project', $userEntity)->where('project_code', $userProject)
            // ->where("tenant_id", $userTenant)
        ;
        return $data;
    }

    public function check_lists() {
        return $this->hasMany(PreventiveMaintenanceGroup::class, 'transaksi_preventive_maintenance_id');
    }

    public function asset_group() {
        return $this->belongsTo(PmAssetGroup::class, 'pm_asset_group_id');
    }

    public function asset_detail() {
        return $this->belongsTo(PmAssetDetail::class, 'pm_asset_detail_id');
    }

    public function getDataIsStatusNew() {
        return $this->with("asset_group", "asset_detail", "check_lists")->whereStatus('1')->orderBy("pm_asset_detail_id", "ASC")->get();
    }
}
