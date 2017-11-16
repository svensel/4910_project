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
    $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/gcal/authcallback');
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
});

Route::get('/gcal/authcallback', function(){
    print_r ($_GET);
});