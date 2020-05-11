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
    Route::post('/', 'ProSituationController@create');
    Route::put('/{id}', 'ProSituationController@update');
    Route::get('/', 'ProSituationController@get');
    Route::get('/{id}', 'ProSituationController@find');
    Route::delete('/{id}', 'ProSituationController@delete');

});

//il s'agit ici des routes pour la gestion gestion du create-update sanction

Route::group(['prefix' => 'sanctions'], function() {
    Route::post('add', 'SanctionController@create');
    Route::match(['put','patch'],'/{id}', 'SanctionController@update');
});

Route::group(['prefix' => 'contact'], function () {
    Route::post('/', 'contactController@saveContact');
    Route::match(['post', 'put'], '/{id}', 'contactController@updateContact');
});

Route::group(['prefix' => 'submissions'], function() {
    Route::get('/', 'SubmissionController@get');
    Route::get('/{id}', 'SubmissionController@find');
    Route::delete('/{id}', 'SubmissionController@delete');

});
//AssignmentType routes
Route::group(['prefix' => 'assignment_types'], function() {
    Route::post('/', 'AssignmentTypeController@create');
    Route::put('/{id}', 'AssignmentTypeController@update');
});

Route::group(['prefix' => 'sanctions'], function () {
    Route::get('/', 'SanctionController@get');
    Route::get('/{id}', 'SanctionController@find');
    Route::delete('/{id}', 'SanctionController@delete');
});
  //Les routes create et update Contracts

  Route::group(['prefix' => 'contracts'], function() {
    Route::post('/', 'ContractController@create');
    Route::post('/{id}', 'ContractController@update');
    Route::get('/{id}', 'ContractController@find');
    Route::get('/', 'ContractController@get');
    Route::delete('/{id}', 'ContractController@delete');
});