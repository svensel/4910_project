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
Route::post('/schedule', 'HomeController@scheduleFinder')->name('scheduleFinder');
Route::get('/help', 'HomeController@help')->name('help');
Route::get('/settings', 'HomeController@settings')->name('settings');
Route::get('/download/{filename}', 'HomeController@download')->name('download');
Route::post('/gcal/auth', 'HomeController@allowGcal')->name('allowGcal');
Route::get('/gcal/authcallback', 'HomeController@callBack')->name('callBack');