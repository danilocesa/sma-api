<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/getToken', 'Auth\AuthController@getToken');
Route::get('/getDialects', 'Auth\AuthController@getDialects');
Route::get('/getRandomText', 'Auth\AuthController@getRandomText');
Route::get('/getUserInfo/{info}', 'Auth\AuthController@userInfo');
Route::get('/getTranslateText/{limit}', 'Auth\AuthController@getTranslateText');
Route::get('/getUserLogged', 'Auth\AuthController@checkUserLogged');
Route::get('/deleteSession', 'Auth\AuthController@deleteSession');
Route::get('/topPlayers', 'Auth\AuthController@getTopPlayers');
Route::get('/getStats', 'Auth\AuthController@getStats');
Route::get('/deductScore', 'Auth\AuthController@deductScore');
Route::get('/userSettings', 'Auth\AuthController@getUserSettings');
Route::post('/saveUserSettings', 'Auth\AuthController@saveUserSettings');
Route::post('/saveTranslate', 'Auth\AuthController@saveTranslate');
Route::post('/saveScore', 'Auth\AuthController@saveScore');
Route::post('/forgot', 'Auth\AuthController@forgot');



// Route::get('yohoo','YohooController@getBasic');
// Route::get('yohoo/logsData','YohooController@getBasicData');

Route::group(['prefix'=>'heymen'],function(){

Route::resource('yohoo','YohooController');


Route::get('players/basic-data','PlayerController@getBasicData');
Route::get('players/top-details/{id}','PlayerController@topDetails');
Route::get('players/top-data/{id}','PlayerController@topData');
Route::resource('players','PlayerController');

});

