<?php

namespace App\Http\Controllers;

use App\Project\ModelFinder;
use App\Project\ScheduleFinder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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

    public function groups()
    {
        return view('pages.groups');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function settings()
    {
        return view('pages.settings', ['user' => Auth::user()]);
    }

    public function scheduleFinder()
    {
        $request = request()->toArray();
        $scheduleFinder = new ScheduleFinder();
        $currentDay = date('w');
        $startOfWeek = strtotime(date('Y-m-d'));
        $startOfWeek = $startOfWeek - (86400 * $currentDay);
        $startOfNextWeek = $startOfWeek + 604800;

        $availableTimes = $scheduleFinder->generateCalendar($request['groupId']);
        $filename = $scheduleFinder->generateSpreadsheet($availableTimes, $startOfWeek, $startOfNextWeek);

        $convertedTimes = array();
        $id = 1;

        foreach($availableTimes as $week){
            foreach($week['available'] as $day){
                foreach($day['times'] as $time){
                    $convertedTimes[] = [
                        'id' => $id++,
                        'text' => 'Available Time',
                        'start' => date('Y-m-d', $startOfWeek).'T'.$time['start'],
                        'end' => date('Y-m-d', $startOfWeek).'T'.$time['end'],
                    ];
                }
                $startOfWeek += 86400;
            }
            $startOfWeek = $startOfNextWeek;
        }

        return view('pages.cal', ['times' => json_encode($convertedTimes), 'filename' => $filename]);
    }

    public function download($filename)
    {
        $headers = array(
            'Content-Type' => 'application/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        );

        return Response::download(public_path('reports/'.$filename), $filename, $headers );
    }
}
