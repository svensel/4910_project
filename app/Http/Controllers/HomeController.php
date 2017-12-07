<?php

namespace App\Http\Controllers;

use App\Project\ModelFinder;
use App\Project\ScheduleFinder;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function allowGcal()
    {
        $request = request()->toArray();
        $user = Auth::user();
        if(!isset($request['allowAccess'])){
            $user->google_cal_access = false;
            $user->access_token = null;
            $user->refresh_token = null;
            $user->save();
            return redirect('/settings')->with('Success');
        }
        elseif($user->google_cal_access == true)
            return redirect('/settings');

        $client = new Google_Client();
        $client->setAuthConfig(base_path('client_secrets.json'));
        $client->setAccessType("offline");        // offline access
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/gcal/authcallback');
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    public function callBack()
    {
        try{
            $auth = $_GET['code'];
            $client = new Google_Client();
            $client->setAuthConfig(base_path('client_secrets.json'));
            $token = $client->fetchAccessTokenWithAuthCode($auth);

            DB::update('UPDATE users SET refresh_token = ?, access_token = ? WHERE id = ?',
                [$token['refresh_token'], base64_encode(serialize($token)), Auth::id()]);
            Auth::user()->google_cal_access = true;
            Auth::user()->save();

            echo  "<script type='text/javascript'>";
            echo "window.close();";
            echo "</script>";

            return redirect('/settings');
        }catch(\Exception $e){
            return view('pages.oops');
        }
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
