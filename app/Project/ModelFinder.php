<?php

namespace App\Project;

use App\Course;
use App\Group;
use Illuminate\Support\Facades\Auth;

/*
 * This class makes it easy for you to get data from the local database
 * */
class ModelFinder
{
    public static function getCourses(){
        return Auth::user()->courses()->get();
    }

    public static function getAuthUser(){
        return Auth::user();
    }

    public static function getCoursesFromUser($user){
        return $user->courses();
    }

    public static function getAuthUserGroups(){
        return Auth::user()->groups()->get();
    }

    public static function getGroupsFromCourse($courseId){
        return Course::find($courseId)->groups()->get();
    }

    public static function getCourseFromGroup($groupId){
        return Group::find($groupId)->course()->get();
    }

    public static function getUsersFromGroup($groupId){
        return Group::find($groupId)->users()->get();
    }
}