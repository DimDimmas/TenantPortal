<?php

namespace App\Model\trackingLoading;

use Illuminate\Database\Eloquent\Model;

class notScanOutModel extends Model
{
  protected $table = 'bm_visit_track_ls_ticket'; 

  protected $fillable = [
    'id_visit_track', 'bak_no', 'entity_project', 'project_no', 'debtor_acct', 'identifier', 'police_no', 'identity_no', 'identity_name', 'created_at', 'created_by'
  ];

  public $timestamps = false;

  public static function boot() {
    parent::boot();

    static::creating(function($model) {
        $model->created_at = date("Y-m-d H:i:s");
        // $model->created_by = auth()->user()->username;
        // $model->updated_at = date("Y-m-d H:i:s");
        // $model->updated_by = auth()->user()->username;
    });

    static::updating(function($model) {
        $model->updated_at = date("Y-m-d H:i:s");
        // $model->updated_by = auth()->user()->username;
    });
  }
}
