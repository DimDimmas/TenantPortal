<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BmVisitTrack extends Model
{
    public $timestamps = false;

    public $table = 'bm_visit_track';

    protected $fillable = [
        "identifier", "entity_project", "project_no", "debtor_acct", "image_capture", "ktp_attachment",
        "scan_in", "scan_out", "user_agent", "type", "plate_area", "police_no", "identity_no", "identity_name",
        "created_at", "created_by", "updated_at", "updated_by"
    ];
}
