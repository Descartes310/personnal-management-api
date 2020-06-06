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

Route::pattern('id', '[0-9]+');

Route::group(['prefix' => 'auth'], function () {

    Route::post('token', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::delete('token', 'AuthController@logout');
        Route::post('updatepassword', 'AuthController@updatePassword');
        Route::get('permissions', 'AuthController@permissions');
        Route::get('roles', 'AuthController@roles');
        Route::get('teams', 'AuthController@teams');

   });

});


Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::post('/', 'UserController@create');
        Route::get('/{id}', 'UserController@getUserInfo');
        Route::get('', 'UserController@getUsers');
        Route::get('/search', 'UserController@search');
        Route::post('/', 'UserController@create');
        Route::delete('/{user}', 'UserController@delete')->where('user', '[0-9]+');
        Route::match(['post', 'put'], '/{id}', 'UserController@update');
    });

    //il s'agit des routes pour read et delete profile
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/getProfiles', 'ProfileController@getProfiles');
        Route::get('/', 'ProfileController@get');
        Route::get('/{id}', 'ProfileController@find');
        Route::delete('/{id}', 'ProfileController@delete');
        Route::post('/', 'ProfileController@create');
        Route::post('/{id}', 'ProfileController@update');
    });

    Route::group(['prefix' => 'roles'], function () { 
        Route::get('/getRolesWithPermissions', 'RoleController@getRolesWithPermissions');
        Route::get('/', 'RoleController@get');
        Route::post('/', 'RoleController@store');
        Route::post('/{id}', 'RoleController@update');
        Route::delete('/{id}', 'RoleController@delete');
        Route::get('/{id}', 'RoleController@find');
    });

    Route::get('/permissions', 'RoleController@getPermissions');
    Route::match(['patch', 'post', 'put'], '/sync_user_abilities/{id}', 'RoleController@syncAbilities');


    Route::group(['prefix' => 'profile_updates'], function () {
        Route::get('/', 'ProfileUpdateController@get');
        Route::get('{id}', 'ProfileUpdateController@find');
        Route::delete('{id}', 'ProfileUpdateController@delete');
        Route::post('/', 'ProfileUpdateController@create');
        Route::match(['post','put'], '/{id}', 'ProfileUpdateController@update');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@get');
        Route::delete('/{id}', 'SettingController@delete');
        Route::get('/{id}', 'SettingController@find');
        Route::post('/', 'SettingController@create');
        Route::put('/{id}', 'SettingController@update');
        Route::post('/{id}', 'SettingController@update');
    });

    Route::group(['prefix' => 'hotels'], function () {
        Route::get('/', 'HotelController@allHotel');
        Route::delete('/{id}', 'HotelController@destroyHotel');
        Route::get('/search', 'HotelController@searchHotel');
        Route::post('/', 'HotelController@createHotel');
        Route::match(['put', 'post'], '/{id}', 'HotelController@updateHotel');
    });

    Route::group(['prefix' => 'reservations'], function () {
        Route::get('/', 'ReservationController@allReservation');
        Route::delete('/{id}', 'ReservationController@destroyReservation');
        Route::get('/search', 'ReservationController@searchReservation');
        Route::post('/', 'ReservationController@createReservation');
        Route::match(['put', 'post'], '/{id}', 'ReservationController@updateReservation');
    });

    Route::group(['prefix' => 'user_reservations'], function () {
        Route::get('/', 'UserReservationController@allUserReservation');
        Route::delete('/{id}', 'UserReservationController@destroyUserReservation');
        Route::get('/search', 'UserReservationController@searchUserReservation');
        Route::post('/', 'UserReservationController@createUserReservation');
        Route::match(['put', 'post'], '/{id}', 'UserReservationController@updateUserReservation');
    });

    Route::group(['prefix' => 'rooms'], function () {
        Route::get('/', 'ChambreController@allChambre');
        Route::delete('/{id}', 'ChambreController@destroyChambre');
        Route::get('/search', 'ChambreController@searchChambre');
        Route::post('/', 'ChambreController@createChambre');
        Route::match(['put', 'post'], '/{id}', 'ChambreController@updateChambre');
    });
});

