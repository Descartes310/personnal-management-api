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


Route::group(['prefix'=>'license_types'], function(){
	Route::get('/','LicenseTypeController@get');
	Route::delete('/{id}','LicenseTypeController@delete');
	Route::get('/{id}','LicenseTypeController@find');


});
Route::group(['prefix'=>'vacations'], function(){
    Route::get('/','VacationController@get');
    Route::delete('/{id}','VacationController@delete');
    Route::get('/{id}','VacationController@find');


});









