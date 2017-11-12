<?php

namespace App\Project;

use Illuminate\Support\Facades\Auth;

class CourseFinder
{
    public static function getCourses(){
        return Auth::user()->courses()->get();
    }
}