<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Jsonable;

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
Route::group(['prefix'=>'contacts'], function(){
    Route::get('/{id}','ContactController@find');
    Route::get('/', 'ContactController@get');
    Route::get('/{page}', 'ContactController@get');
    //Route::get ('','ContactController@get');
    //Roue::get('', 'ContactController@get'); 
    Route::delete('/{id}', 'ContactController@delete');
});
Route::group(['prefix' => 'careers'], function () {
    Route::post('/', 'CareerController@create');
    Route::match(['put', 'post'],'/{id}', 'CareerController@update');
});