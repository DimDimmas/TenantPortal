<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PmTaskListGroupDetail extends Model
{
    // use SoftDeletes;

    public $table = 'pm_task_list_asset_group_details';

    protected $cast = [
        "is_required" => "boolean",
        "image_required" => "boolean",
    ];

    protected $fillable = [
        "code", "name", "description", "pm_task_list_asset_group_id",
        "is_required", "created_at", "created_by", "updated_at",
        "updated_by", "deleted_at", "image_required", "video_required", "status"
    ];

    
}
