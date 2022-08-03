<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public $table = "ifca_cf_entity";

    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'entity_project';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

     /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        "entity_project", "entity_cd", "entity_name", "bs_div", "bs_dept", "base_currency", "posttransl",
        "address1", "address2", "address3", "post_cd", "telephone_no", "fax_no", "tax_descs", "tax_reg_no",
        "balentity", "fyear", "aperiod", "ver_cd", "audit_user", "audit_date", "budget_ctrl", "budget_acct_dept",
        "rowID", "logo1", "mobile_use", "db_route"
    ];
}
