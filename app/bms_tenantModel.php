<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bms_tenantModel extends Model
{
    //
	protected $casts = [
    		'tenant_token' => 'varchar'
		];
 protected $fillable = [
        'tenant_code', 'tenant_email', 'password'    
    ];

}
