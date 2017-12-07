<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function getTimes()
    {
        return [
            'mon' => $this->mon,
            'tues' => $this->tues,
            'wed' => $this->wed,
            'thur' => $this->thur,
            'fri' => $this->fri,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time
        ];
    }
}
