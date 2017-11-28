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

use App\Course;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function(){return redirect('home');})->name('/');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/groups', 'HomeController@groups')->name('groups');
Route::post('/schedule{id}', 'HomeController@scheduleFinder')->name('scheduleFinder'); //probably a route like this that goes to the HomeController to generate schedules
Route::get('/help', 'HomeController@help')->name('help');
Route::get('/settings', 'HomeController@settings')->name('settings');

Route::post('/gcal/auth', function(){
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
})->name('allowGcal');

Route::get('/gcal/authcallback', function(){
    
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
    
});

Route::get('/events', function(){
   $api = new \App\Project\GoogleApi(Auth::user()->id);
   dd($api->fetch_events());
});