<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function courses(){
        return $this->belongsTo('App\Course');
    }

    public function user(){
        return $this->belongsToMany('App\User');
    }
}
