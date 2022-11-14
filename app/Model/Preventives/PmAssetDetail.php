<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;

class PmAssetDetail extends Model
{
    public $table = 'pm_asset_details';

    public $primaryKey = 'id';

    protected $fillable = [
        "pm_asset_id", 'barcode', 'last_date_pm', 'is_assigned', 'status', "purchase_date",
        "created_at", "created_by", "updated_at", "updated_by", "last_day_pm", "asset_name"
    ];

    protected $casts = [
        "barcode" => "string",
    ];
}
