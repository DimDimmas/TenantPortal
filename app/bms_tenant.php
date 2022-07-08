<?php

namespace App;

use LdapRecord\Laravel\Auth\HasLdapUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class bms_tenant extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['password'];
    
    protected $fillable = [
        // 'name', 'email', 'password',
        'tenant_code', 'tenant_email', 'password'    
    ];

    protected $table = 'bms_tenant';
    //protected  $primaryKey = 'tenant_token';
    // public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    // protected $keyType = 'string';
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'tenant_token', 'password',
    ];
}