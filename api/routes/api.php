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

    Route::post('/announcements', 'AnnouncementController@set');

    Route::delete('/auth', 'UserController@logout');
    Route::get('/auth', 'UserController@refresh');
    Route::put('/auth', 'UserController@login');
    Route::post('/auth', 'UserController@register');

    Route::get('/missing_fields', 'UserController@getMissingFields');
    Route::post('/reset_password', 'UserController@resetPassword');
    Route::put('/update_password', 'UserController@updatePassword');
    Route::put('/user', 'UserController@update');

    Route::get('/data/{type}', 'DataController@getData');

    Route::post('/paid', 'PaidServiceController@deposit');
    Route::post('/deposit_confirmation', 'PaidServiceController@depositConfirmation');
    Route::get('/paid', 'PaidServiceController@get');

    Route::get('/profile/{id}', 'ProfileController@getProfileWithId');
    Route::get('/profile', 'ProfileController@getProfile');
    Route::put('/profile', 'ProfileController@updateProfile');

    Route::put('/picture', 'ProfileController@uploadProfilePicture');

    Route::post('/one_signal', 'OneSignalController@set');

    Route::get('/reports', 'ReportController@get');
    Route::get('/reports/{type}', 'ReportController@get');

    Route::get('/recommended', 'SearchController@getRecommendedTutors');
    Route::get('/search', 'SearchController@get');

    Route::delete('/students/{student_id}', 'StudentController@delete');
    Route::get('/students', 'StudentController@get');
    Route::post('/students', 'StudentController@set');
    Route::put('/students', 'StudentController@update');

    Route::get('/suitabilities', 'SuitabilityController@get');
    Route::put('/suitabilities/{type}', 'SuitabilityController@update');

    Route::delete('/tutor_lectures/{tutor_lecture_id}', 'TutorLectureController@delete');
    Route::get('/tutor_lectures', 'TutorLectureController@get');
    Route::post('/tutor_lectures', 'TutorLectureController@set');

    Route::get('/offers', 'OfferController@get');
    Route::get('/received_offers_count', 'OfferController@getReceivedOffersCount');
    Route::post('/offers', 'OfferController@set');
    Route::put('/offers/{offerId}', 'OfferController@updateStatus');

});
