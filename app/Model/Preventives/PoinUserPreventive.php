<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;

class PoinUserPreventive extends Model
{
    public $table = 'poin_user_preventive';

    protected $fillable = [
        'username', 'point', 'description', 'created_at', 'updated_at',
    ];

    protected $guarded = ['id'];
}
