<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
