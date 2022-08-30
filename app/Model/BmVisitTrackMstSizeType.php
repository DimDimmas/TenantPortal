<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BmVisitTrackMstSizeType extends Model
{
    public $table = 'bm_visit_track_mst_size_types';

    protected $fillable = [
        "code", "name", "description", "created_at", "created_by", "updated_at", "updated_by", "debtor_acct"
    ];

    protected $guard = ['id'];

    public function generateCode() {
       $query = $this->select("code")->orderBy("id", "desc")->first();

       $urutan = null;

       if(is_null($query) || is_null($query->code)) {
            $urutan = 0;
       } else {
            $urutan = (int) substr($query->code, 3, 4);
       }

       $firstPrefix = "BMT";
       $urutan += 1;

       $newCode = $firstPrefix . sprintf("%04s", $urutan);
       return $newCode;
    }

    public function createNew($data) {
        unset($data['code']);
        $data['code'] = $this->generateCode();
        $data['created_at'] = date("Y-m-d H:i:s", time());
        $data['created_by'] = auth()->user()->tenant_code;
        $data['updated_at'] = date("Y-m-d H:i:s", time());
        $data['updated_by'] = auth()->user()->tenant_code;
        $data['debtor_acct'] = auth()->user()->tenant_code;
        return $this->insert($data);
    }

    public function updateData($dataExist, $data) {
        unset($data['code']);
        $data['updated_at'] = date("Y-m-d H:i:s", time());
        $data['updated_by'] = auth()->user()->tenant_code;
        return $dataExist->update($data);
    }
    
}
