<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $guarded = [];
    public function sub_type(){
        return $this->hasMany('App\TicketType', 'form_id');
    }
}
