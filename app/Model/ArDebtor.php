<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArDebtor extends Model
{
    public $table = 'ifca_ar_debtor';

    public $timestamps = false;

    protected $fillable = [
        "entity_project", "entity_cd", "project_no", "debtor_acct", "project_no", "name", "class", "business_id",
        "type", "remarks", "status", "reason_id", "contact_person", "address1", "address2", "address3", "post_cd",
        "cash_advance", "stamp_flag", "tax_invoice_type", "open_status", "debtor_status", "status_posting",
        "bast_date", "virtual_acct", "entitas_cd", 
    ];

    public function entity() {
        return $this->belongsTo("App\Model\Entity", "entity_project");
    }

    public function project() {
        return $this->belongsTo("App\Model\Project", "project_no");
    }

}
