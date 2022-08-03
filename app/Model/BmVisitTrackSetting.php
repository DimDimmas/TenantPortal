<?php

namespace App\Model;

use DateTime;
use Illuminate\Database\Eloquent\Model as models;

class BmVisitTrackSetting extends models
{
    public $table = 'bm_visit_track_settings';

    protected $guard = ['id'];

    protected $fillable = [
        "entity_project", "project_no", "debtor_acct", "type", "name", "description", "created_at", "created_by",
        "updated_at", "updated_by", "value", "status", "bm_visit_track_mst_size_type_id"
    ];

    public function entity() {
        return $this->belongsTo("App\Model\Entity", "entity_project");
    }

    public function project() {
        return $this->belongsTo("App\Model\Project", "project_no");
    }

    public function ar_debtor() {
        return $this->belongsTo("App\Model\ArDebtor", "debtor_acct");
    }

    public function size_type() {
        return $this->belongsTo("App\Model\BmVisitTrackMstSizeType", "bm_visit_track_mst_size_type_id");
    }

    public function statuses() {
        return [
            "inactive" => "INACTIVE",
            "active" => "ACTIVE"
        ];
    }

    public function types() {
        return [
            "general" => "GENERAL",
            "inbound" => "INBOUND",
            "outbound" => "OUTBOUND",
            // "langsir" => "LANGSIR",
        ];
    }

    public function createNew(array $data) {
        $data['created_at'] = new DateTime();
        $data['created_by'] = auth()->user()->tenant_code;
        $data['updated_at'] = new DateTime();
        $data['updated_by'] = auth()->user()->tenant_code;

        return $this->create($data);
    }

    public function updateExists($model, array $data) {
        $data['updated_at'] = new DateTime();
        $data['updated_by'] = auth()->user()->tenant_code;
        return $model->update($data);
    }

    
}
