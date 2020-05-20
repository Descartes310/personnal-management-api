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
        Route::get('permissions', 'AuthController@permissions');
        Route::get('roles', 'AuthController@roles');
        Route::get('teams', 'AuthController@teams');
    });

});


Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('/{id}', 'UserController@getUserInfo');
        Route::get('', 'UserController@getUsers');
        Route::get('/search', 'UserController@search');
        Route::post('/', 'UserController@create');
        Route::delete('/{user}', 'UserController@delete')->where('user', '[0-9]+');
        Route::match(['post', 'put'], '/{id}', 'UserController@update');
    });


    Route::group(['prefix' => 'blog_categories'], function () {
        Route::delete('/{id}', 'BlogCategoryController@delete');
        Route::get('/{id}', 'BlogCategoryController@find');
        Route::get('', 'BlogCategoryController@get');
    });


    Route::group(['prefix' => 'assignments'], function () {
        Route::delete('/{id}', 'AssignmentController@delete');
        Route::get('/{id}', 'AssignmentController@find');
        Route::get('/', 'AssignmentController@get');
        Route::post('/', 'AssignmentController@create');
        Route::put('/{id}', 'AssignmentController@update');
    });


    Route::group(['prefix' => 'pro_situations'], function () {
        Route::delete('/{id}', 'ProSituationController@delete');
        Route::get('/{id}', 'ProSituationController@find');
        Route::get('/', 'ProSituationController@get');
        Route::post('/', 'ProSituationController@create');
        Route::match(['post', 'put'], '/{id}', 'ProSituationController@update');
    });


    Route::group(['prefix' => 'contracts'], function () {
        Route::post('', 'ContractController@create');
        Route::post('/{id}', 'ContractController@update');
        Route::get('/{id}', 'ContractController@find');
        Route::get('', 'ContractController@get');
        Route::delete('/{id}', 'ContractController@delete');
        Route::get('/print-pdf/{id}','ContractController@printPDF');
    });



    Route::group(['prefix' => 'divisions'], function () {
        Route::post('/', 'DivisionController@create');
        Route::put('/{id}', 'DivisionController@update');
        Route::get('/', 'DivisionController@get');
        Route::get('/{id}', 'DivisionController@find');
        Route::delete('/{id}', 'DivisionController@delete');
    });

    Route::group(['prefix' => 'note_criterias'], function () {
        Route::post('/', 'NoteCriteriaController@create');
        Route::put('/{id}', 'NoteCriteriaController@update');
        Route::get('/{id}', 'NoteCriteriaController@find');
        Route::get('/', 'NoteCriteriaController@get');
        Route::delete('/{id}', 'NoteCriteriaController@delete');
    });

    Route::group(['prefix' => 'vacation_types'], function () {
        Route::get('', 'VacationTypeController@get');
        Route::get('/{id}', 'VacationTypeController@find');
        Route::delete('/{id}', 'VacationTypeController@delete');
        Route::post('/', 'VacationTypeController@create');
        Route::match(['put', 'post'],'/{id}', 'VacationTypeController@update');
    });

    Route::group(['prefix' => 'disciplinary_boards'], function () {
        Route::get('/', 'DisciplinaryBoardController@get');
        Route::get('/{id}', 'DisciplinaryBoardController@find');
        Route::delete('/{id}', 'DisciplinaryBoardController@delete');
        Route::post('/', 'DisciplinaryBoardController@create');
        Route::put('/{id}', 'DisciplinaryBoardController@update');
    });


    Route::group(['prefix' => 'licenses'], function () {
        Route::get('/', 'LicenseController@get');
        Route::delete('{id}', 'LicenseController@delete');
        Route::get('{id}', 'LicenseController@find');
        Route::delete('lchangeStatus/{id}', 'LicenseController@changeStatus');
    });



    Route::group(['prefix' => 'blog_posts'], function () {
        Route::get('/', 'BlogPostController@get');
        Route::delete('{id}', 'BlogPostController@delete');
        Route::get('{id}', 'BlogPostController@find');
        Route::post('/', 'BlogPostController@create')->middleware('has-permission:create-blog-post');
        Route::match(['post','put'], '/{id}', 'BlogPostController@update');
    });


    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::get('/{id}', 'DisciplinaryTeamController@find');
        Route::get('/', 'DisciplinaryTeamController@get');
        Route::delete('/{id}', 'DisciplinaryTeamController@delete');
    });


    Route::group(['prefix' => 'templates'], function () {
        Route::get('/', 'TemplateController@get');
        Route::delete('{id}', 'TemplateController@delete');
        Route::get('{id}', 'TemplateController@find');
        Route::post('/', 'TemplateController@create');
        Route::put('/{id}', 'TemplateController@update');
    });

    //il s'agit des routes pour read et delete profile
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/', 'ProfileController@get');
        Route::get('/{id}', 'ProfileController@find');
        Route::delete('/{id}', 'ProfileController@delete');
        Route::post('/', 'ProfileController@create');
        Route::put('/{id}', 'ProfileController@update');
    });

    Route::group(['prefix' => 'sanctions'], function () {
        Route::post('/', 'SanctionController@create');
        Route::put('/{id}', 'SanctionController@update');
        Route::get('/', 'SanctionController@get');
        Route::get('/{id}', 'SanctionController@find');
        Route::delete('/{id}', 'SanctionController@delete');
    });

    Route::group(['prefix' => 'careers'], function () {
        Route::get('/', 'CareerController@get');
        Route::get('/{id}', 'CareerController@find');
        Route::delete('/{id}', 'CareerController@delete');
        Route::post('/', 'CareerController@create');
        Route::match(['put', 'post'], '/{id}', 'CareerController@update');
    });


    Route::group(['prefix' => 'trainings'], function () {
        Route::get('/', 'TrainingController@get');
        Route::get('/{id}', 'TrainingController@find');
        Route::delete('/{id}', 'TrainingController@delete');
    });


    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::post('/', 'DisciplinaryTeamController@create');
        Route::match(['put', 'post'], '/{id}', 'DisciplinaryTeamController@update');
        Route::get('/{id}', 'DisciplinaryTeamController@find');
        Route::get('/', 'DisciplinaryTeamController@get');
        Route::delete('/{id}', 'DisciplinaryTeamController@delete');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RoleController@get');
        Route::post('/', 'RoleController@store');
        Route::post('/{id}', 'RoleController@update');
        Route::delete('/{id}', 'RoleController@delete');
        Route::get('/{id}', 'RoleController@find');
    });

    Route::get('/permissions', 'RoleController@getPermissions');


    Route::group(['prefix' => 'profile_updates'], function () {
        Route::get('/', 'ProfileUpdateController@get');
        Route::get('{id}', 'ProfileUpdateController@find');
        Route::delete('{id}', 'ProfileUpdateController@delete');
        Route::post('/', 'ProfileUpdateController@create');
        Route::match(['post','put'], '/{id}', 'ProfileUpdateController@update');
    });


    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/{id}', 'ContactController@find');
        Route::get('/', 'ContactController@get');
        Route::delete('/{id}', 'ContactController@delete');
        Route::post('/', 'ContactController@saveContact');
        Route::put('/{id}', 'ContactController@updateContact');
    });

    Route::group(['prefix' => 'chats'], function () {
        Route::post('', 'ChatController@newMessage');
        Route::get('/discussion/{id}', 'ChatController@discussionMessage');
        Route::get('/discussion/{id}/newmessages', 'ChatController@getNewMessages');
        Route::delete('/discussion/{id}', 'ChatController@deleteDiscussion');
        Route::delete('/{id}', 'ChatController@deleteMessage');
        Route::get('/discussions/{id}', 'ChatController@getDiscussions');
    });

    Route::group(['prefix' => 'assignment_types'], function () {
        Route::post('/', 'AssignmentTypeController@create');
        Route::post('/{id}', 'AssignmentTypeController@update');
        Route::get('/{id}', 'AssignmentTypeController@find');
        Route::post('/', 'AssignmentTypeController@create');
        Route::put('/{id}', 'AssignmentTypeController@update');
    });


    Route::group(['prefix' => 'license_types'], function () {
        Route::get('/', 'LicenseTypeController@get');
        Route::delete('/{id}', 'LicenseTypeController@delete');
        Route::get('/{id}', 'LicenseTypeController@find');
        Route::post('/', 'LicenseTypeController@add');
        Route::post('/{id}', 'LicenseTypeController@update');
        Route::put('/{id}', 'LicenseTypeController@update');
    });

    Route::group(['prefix' => 'licenses'], function () {
        Route::post('/','LicenseController@create');
        Route::post('/{id}','LicenseController@update');
        Route::get('/', 'LicenseController@get');
        Route::delete('{id}', 'LicenseController@delete');
        Route::get('{id}', 'LicenseController@find');
        Route::patch('{id}/changeStatus', 'LicenseController@changeStatus');
    });


    Route::group(['prefix' => 'vacations'], function () {
        Route::get('/', 'VacationController@get');
        Route::delete('/{id}', 'VacationController@delete');
        Route::get('/{id}', 'VacationController@find');
        Route::post('/', 'VacationController@create');
        Route::match(['post', 'put'], '/{id}', 'VacationController@update');
    });

    Route::group(['prefix' => 'submissions'], function () {
        Route::get('/', 'SubmissionController@get');
        Route::delete('/{id}', 'SubmissionController@delete');
        Route::get('/{id}', 'SubmissionController@find');
        Route::post('submissions', 'SubmissionController@create');
        Route::match(['put', 'post'], 'submissions/{id}', 'SubmissionController@update');
    });

});

