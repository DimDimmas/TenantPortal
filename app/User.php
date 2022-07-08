<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_code', 'tenant_person', 'tenant_email', 'pic_email1', 'pic_email2',
        // 'tenant_token', 'password',
    ];

    protected $table = 'bms_tenant';
    protected  $primaryKey = 'tenant_id';
    // public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    // protected $keyType = 'string';
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pic_password1',
        'pic_password2',
        'remember_token',
    ];
}