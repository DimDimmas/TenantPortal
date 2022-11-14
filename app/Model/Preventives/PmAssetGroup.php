<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;

class PmAssetGroup extends Model
{
    public $table = 'pm_asset_groups';

    public $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = false;

    public $fillable = [
        'code', 'name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'pm_schedule_time',
        "doc_form_code"
    ];
}
