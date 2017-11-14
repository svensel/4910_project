<?php

namespace App\Http\Controllers;

use App\Project\ModelFinder;
use App\Project\ScheduleFinder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = ModelFinder::getCourses();
        $numCourses = count($courses);
        $numRows = intdiv($numCourses,2);

        if($numCourses % 2 > 0)
            $numRows++;

        return view('pages.dashboard', [
            'courses' => $courses,
            'numRows' => $numRows,
            'numCourses' =>$numCourses
        ]);
    }

    public function groups(){
        return view('pages.groups');
    }

    public function scheduleFinder(){
        $scheduleFinder = new ScheduleFinder();
        return view('welcome'); //TEMPORARY

        //Something like what is below
        //return view('pages.schedule', $scheduleFinder->generateSchedule())
    }
}