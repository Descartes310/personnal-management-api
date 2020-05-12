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


Route::get('/licenses/{limit?}','LicenseController@get');
Route::delete('licenses/{id}','LicenseController@delete');
Route::get('licenses/{id}','LicenseController@find');
Route::delete('licenses/changeStatus/{id}','LicenseController@changeStatus');

Route::group(['prefix' => 'blogposts'], function () {
	Route::get('/', 'BlogPostController@get');
	Route::get('/{id}', 'BlogPostController@find');
	Route::delete('/', 'BlogPostController@delete');
});