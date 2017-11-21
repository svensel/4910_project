<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function(){return redirect('home');})->name('/');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/groups', 'HomeController@groups')->name('groups');
Route::post('/schedule{id}', 'HomeController@scheduleFinder')->name('scheduleFinder'); //probably a route like this that goes to the HomeController to generate schedules
Route::get('/help', 'HomeController@help')->name('help');

Route::get('/test', function(){

});

Route::get('/gcal/auth', function(){
    $client = new Google_Client();
    $client->setAuthConfig('../client_secrets.json');
    $client->setAccessType("offline");        // offline access
    $client->setIncludeGrantedScopes(true);   // incremental auth
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/gcal/authcallback');
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
});

Route::get('/gcal/authcallback', function(){
    
    $auth = $_GET['code'];
    $client = new Google_Client();
    $client->setAuthConfig('../client_secrets.json');
    $token = $client->fetchAccessTokenWithAuthCode($auth);
    $calendarService = new Google_Service_Calendar($client);
    /*
    $params = [
        'singleEvents'=>true,
        'timeMax' => "2017-11-25T23:59:59Z",
        'timeMin' => "2017-11-19T00:00:00Z",
    ];

    $events = $calendarService->events->listEvents('primary', $params);
    

    $googleTimes = [];
    $googleEvent = [];
    //whatever format wanted by the team
    foreach ($events["items"] as $item) {
        $startTime = $item->start->dateTime;
        $endTime = $item->end->dateTime;
        $googleEvent["start"] = $startTime;
        $googleEvent["end"] = $endTime;
        array_push($googleTimes, $googleEvent);
    }
    print_r($googleTimes);
    */

    DB::update('UPDATE users SET refresh_token = ?, access_token = ? WHERE id = ?',
     [$token['refresh_token'], base64_encode(serialize($token)), Auth::id()]);
    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "</script>";
    
});