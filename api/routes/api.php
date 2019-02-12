<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

header('Access-Control-Allow-Origins:*');
header('Access-Control-Allow-Methods:*');

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {
    /*Route::get('/clear-cache', function() {
        $route = Artisan::call('route:clear');
        $config = Artisan::call('config:clear');
        $cache = Artisan::call('cache:clear');
        echo 'route: '.$route.'<br>'.
            'config: '.$config.'<br>'.
            'cache: '.$cache;
    });*/

    Route::delete('child', 'ChildController@removeChild');
    Route::post('child', 'ChildController@addNewChild');
    Route::get('child', 'ChildController@getUserChildren');

    Route::get('/data', 'DataController@get');

    Route::get('/suitabilitySchedule', 'SuitabilityScheduleController@getSuitabilitySchedule');
    Route::put('/suitabilitySchedule', 'SuitabilityScheduleController@updateSuitabilitySchedule');

    Route::delete('/userClassList', 'UserClassListController@removeClass');
    Route::get('/userClassList', 'UserClassListController@getUserClassList');
    Route::post('/userClassList', 'UserClassListController@addToUserClassList');
    Route::put('/userClassList', 'UserClassListController@updateClass');

    Route::delete('/userLecturesList', 'LectureController@removeLectureFromUserLectureList');    
    Route::get('/userLecturesList', 'LectureController@getUserLectureList');
    Route::post('/userLecturesList', 'LectureController@addToUserLectureList');

    Route::get('/refreshUser', 'UserController@refreshUser');
    Route::post('/login', 'UserController@auth');
    Route::post('/logout', 'UserController@logout');
    Route::post('/register', 'UserController@register');

    Route::get('/profile', 'ProfileController@getUserProfile');
    Route::put('/profile', 'ProfileController@updateProfile');
    Route::put('/profilePicture', 'ProfileController@uploadProfilePicture');

    Route::get('/search', 'SearchController@search');
});
