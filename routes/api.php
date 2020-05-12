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

Route::group(['prefix' => 'contract'], function () {
    Route::get('', 'ContractController@get');  
});

Route::group(['prefix' => 'contracts'],function(){
    Route::get('/{id}', 'ContractController@find');
    Route::delete('/{id}', 'ContractController@delete');
});

Route::group(['prefix' => 'template'], function () {
    Route::get('', 'TemplateController@get');  
});

Route::group(['prefix' => 'templates'],function(){
    Route::get('/{id}', 'TemplateController@find');
    Route::delete('/{id}', 'TemplateController@delete');
});