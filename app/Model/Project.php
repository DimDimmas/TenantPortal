<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $table = "ifca_pl_project";

    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'project_no';

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
        "entity_project", "entity_cd", "project_no", "project_ref", "debtor_acct", "owner_type", "location", "project_type",
        "descs", "award_date", "start_date", "completion_date"
    ];
}
