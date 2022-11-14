<?php

namespace App\Model\Preventives;

use App\PMLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreventiveMaintenanceToCorrective extends Model
{
    public $table = 'preventive_maintenance_to_correctives';

    public $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable =  [
        'trans_code', 'entity_project',
        'project_code', 'location_id', 'assign_to', 'pm_asset_group_id',
        'pm_asset_id', 'pm_asset_detail_id',
        'created_at', 'is_corrective', 'updated_at', "tenant_id",
        "created_by", "updated_by",
    ];

}
