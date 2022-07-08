<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requestTicket extends Model
{

    protected $table = "bms_tenant_ticket";

    protected $fillable = [        
        'status_id'
    ];
}
