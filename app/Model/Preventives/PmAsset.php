<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;

class PmAsset extends Model
{
    public $table = "pm_assets";

    public $timestamps = false;

    protected $fillable = [
        "code", "type", "brand", "specification", "asset_code_ifca", "asset_group_id",
        "status", "quantity", "created_at", "created_by", "updated_at", "updated_by",
        "due_days",
    ];

    public function maintenance() {
        return $this->hasMany(Maintenance::class, 'pm_asset_id');
    }
}
