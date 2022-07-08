<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class overtimeModel extends Model
{
    protected $table = "bms_overtime";

    protected $fillable = [
        'overtime_code',
        'overtime_date',
        'overtime_user'
    ];
}
