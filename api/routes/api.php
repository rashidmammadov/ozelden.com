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

    Route::delete('/auth', 'UserController@logout');
    Route::get('/auth', 'UserController@refresh');
    Route::put('/auth', 'UserController@login');
    Route::post('/auth', 'UserController@register');

    Route::get('/data/{type}', 'DataController@getData');

    Route::post('/paid', 'PaidServiceController@deposit');
    Route::post('/deposit_confirmation', 'PaidServiceController@depositConfirmation');
    Route::get('/paid', 'PaidServiceController@get');

    Route::get('/profile', 'ProfileController@getProfile');
    Route::put('/profile', 'ProfileController@updateProfile');

    Route::post('/picture', 'ProfileController@uploadProfilePicture');

    Route::get('/search', 'SearchController@get');

    Route::get('/suitabilities', 'SuitabilityController@get');
    Route::put('/suitabilities/{type}', 'SuitabilityController@update');

    Route::delete('/tutor_lectures/{tutor_lecture_id}', 'TutorLectureController@delete');
    Route::get('/tutor_lectures', 'TutorLectureController@get');
    Route::post('/tutor_lectures', 'TutorLectureController@set');
});
