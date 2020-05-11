<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {

    Route::post('token', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::delete('token', 'AuthController@logout');
        Route::get('permissions', 'AuthController@permissions');
        Route::get('roles', 'AuthController@roles');
        Route::get('teams', 'AuthController@teams');
    });
});

Route::group(['prefix' => 'pro_situations'], function() {
    Route::get('/', 'ProSituationController@get');
    Route::get('/{id}', 'ProSituationController@find');
    Route::delete('/{id}', 'ProSituationController@delete');

});

Route::group(['prefix' => 'submissions'], function() {
    Route::get('/', 'SubmissionController@get');
    Route::get('/{id}', 'SubmissionController@find');
    Route::delete('/{id}', 'SubmissionController@delete');

});