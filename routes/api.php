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
        Route::post('/', 'UserController@create')->middleware('has-permission:create-users');
        Route::get('/{id}', 'UserController@getUserInfo')->middleware('has-permission:read-users');
        Route::get('/', 'UserController@getUsers')->middleware('has-permission:read-users');
        Route::get('/search', 'UserController@search')->middleware('has-permission:read-users');
        Route::delete('/{user}', 'UserController@delete')->where('user', '[0-9]+')->middleware('has-permission:delete-users');
        Route::match(['post', 'put'], '/{id}', 'UserController@update')->middleware('has-permission:update-users');
    });


    Route::group(['prefix' => 'blog_categories'], function () {
        Route::delete('/{id}', 'BlogCategoryController@delete')->middleware('has-permission:delete-blog-categories');
        Route::get('/{id}', 'BlogCategoryController@find')->middleware('has-permission:read-blog-categories');
        Route::get('/', 'BlogCategoryController@get')->middleware('has-permission:read-blog-categories');
        Route::post('/', 'BlogCategoryController@create')->middleware('has-permission:create-blog-categories');
        Route::post('/{id}', 'BlogCategoryController@update')->middleware('has-permission:update-blog-categories');
    });


    Route::group(['prefix' => 'assignments'], function () {
        Route::delete('/{id}', 'AssignmentController@delete')->middleware('has-permission:delete-assignments');
        Route::get('/{id}', 'AssignmentController@find')->middleware('has-permission:read-assignments');
        Route::get('/', 'AssignmentController@get')->middleware('has-permission:read-assignments');
        Route::post('/', 'AssignmentController@create')->middleware('has-permission:create-assignments');
        Route::match(['post', 'put'], '/{id}', 'AssignmentController@update')->middleware('has-permission:update-assignments');
    });


    Route::group(['prefix' => 'pro_situations'], function () {
        Route::delete('/{id}', 'ProSituationController@delete')->middleware('has-permission:delete-pro-situations');
        Route::get('/{id}', 'ProSituationController@find')->middleware('has-permission:read-pro-situations');
        Route::get('/', 'ProSituationController@get')->middleware('has-permission:read-pro-situations');
        Route::post('/', 'ProSituationController@create')->middleware('has-permission:create-pro-situations');
        Route::match(['post', 'put'], '/{id}', 'ProSituationController@update')->middleware('has-permission:update-pro-situations');
    });


    Route::group(['prefix' => 'contracts'], function () {
        Route::post('', 'ContractController@create')->middleware('has-permission:create-contracts');
        Route::post('/{id}', 'ContractController@update')->middleware('has-permission:update-contracts');
        Route::get('/{id}', 'ContractController@find')->middleware('has-permission:read-contracts');
        Route::get('', 'ContractController@get')->middleware('has-permission:read-contracts');
        Route::delete('/{id}', 'ContractController@delete')->middleware('has-permission:delete-contracts');
        Route::get('/print-pdf/{id}','ContractController@printPDF')->middleware('has-permission:read-contracts');
    });

    Route::group(['prefix' => 'submissions'], function () {
        Route::post('/', 'SubmissionController@create')->middleware('has-permission:create-submissions');
        Route::post('/{id}', 'SubmissionController@update')->middleware('has-permission:update-submissions');
        Route::get('/', 'SubmissionController@get')->middleware('has-permission:read-submissions');
        Route::get('/{id}', 'SubmissionController@find')->middleware('has-permission:read-submissions');
        Route::delete('/{id}', 'SubmissionController@delete')->middleware('has-permission:delete-submissions');
    });

    Route::group(['prefix' => 'divisions'], function () {
        Route::post('/', 'DivisionController@create')->middleware('has-permission:create-divisions');
        Route::post('/{id}', 'DivisionController@update')->middleware('has-permission:update-divisions');
        Route::get('/', 'DivisionController@get')->middleware('has-permission:read-divisions');
        Route::get('/{id}', 'DivisionController@find')->middleware('has-permission:read-divisions');
        Route::delete('/{id}', 'DivisionController@delete')->middleware('has-permission:delete-divisions');
    });

    Route::group(['prefix' => 'note_criterias'], function () {
        Route::post('/', 'NoteCriteriaController@create')->middleware('has-permission:create-note-criterias');
        Route::post('/{id}', 'NoteCriteriaController@update')->middleware('has-permission:update-note-criterias');
        Route::get('/{id}', 'NoteCriteriaController@find')->middleware('has-permission:read-note-criterias');
        Route::get('/', 'NoteCriteriaController@get')->middleware('has-permission:read-note-criterias');
        Route::delete('/{id}', 'NoteCriteriaController@delete')->middleware('has-permission:delete-note-criterias');
    });

    Route::group(['prefix' => 'vacation_types'], function () {
        Route::get('', 'VacationTypeController@get')->middleware('has-permission:read-vacation-types');
        Route::get('/{id}', 'VacationTypeController@find')->middleware('has-permission:read-vacation-types');
        Route::delete('/{id}', 'VacationTypeController@delete')->middleware('has-permission:delete-vacation-types');
        Route::post('/', 'VacationTypeController@create')->middleware('has-permission:create-vacation-types');
        Route::match(['put', 'post'],'/{id}', 'VacationTypeController@update')->middleware('has-permission:update-vacation-types');
    });

    Route::group(['prefix' => 'disciplinary_boards'], function () {
        Route::get('/', 'DisciplinaryBoardController@get')->middleware('has-permission:read-disciplinary-boards');
        Route::get('/{id}', 'DisciplinaryBoardController@find')->middleware('has-permission:read-disciplinary-boards');
        Route::delete('/{id}', 'DisciplinaryBoardController@delete')->middleware('has-permission:delete-disciplinary-boards');
        Route::post('/', 'DisciplinaryBoardController@create')->middleware('has-permission:create-disciplinary-boards');
        Route::match(['post','put'], '/{id}', 'DisciplinaryBoardController@update')->middleware('has-permission:update-disciplinary-boards');
    });

    Route::group(['prefix' => 'blog_posts'], function () {
        Route::get('/', 'BlogPostController@get')->middleware('has-permission:read-blog-posts');
        Route::delete('{id}', 'BlogPostController@delete')->middleware('has-permission:delete-blog-posts');
        Route::get('{id}', 'BlogPostController@find')->middleware('has-permission:read-blog-posts');
        Route::post('/', 'BlogPostController@create')->middleware('has-permission:create-blog-posts');
        Route::match(['post','put'], '/{id}', 'BlogPostController@update')->middleware('has-permission:update-blog-posts');
    });

    Route::group(['prefix' => 'disciplinary_team_user'], function () {
        Route::get('/{user_id}','DisciplinaryTeamUserController@find')->middleware('has-permission:read-disciplinary-teams');
        Route::get('/','DisciplinaryTeamUserController@get')->middleware('has-permission:read-disciplinary-teams');
    });

    Route::group(['prefix' => 'templates'], function () {
        Route::get('/', 'TemplateController@get')->middleware('has-permission:read-templates');
        Route::delete('{id}', 'TemplateController@delete')->middleware('has-permission:delete-templates');
        Route::get('{id}', 'TemplateController@find')->middleware('has-permission:read-templates');
        Route::post('/', 'TemplateController@create')->middleware('has-permission:create-templates');
        Route::post('/{id}', 'TemplateController@update')->middleware('has-permission:update-templates');
    });

    //il s'agit des routes pour read et delete profile
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/getProfiles', 'ProfileController@getProfiles')->middleware('has-permission:read-profiles');
        Route::get('/', 'ProfileController@get')->middleware('has-permission:read-profiles');
        Route::get('/{id}', 'ProfileController@find')->middleware('has-permission:read-profiles');
        Route::delete('/{id}', 'ProfileController@delete')->middleware('has-permission:delete-profiles');
        Route::post('/', 'ProfileController@create')->middleware('has-permission:create-profiles');
        Route::post('/{id}', 'ProfileController@update')->middleware('has-permission:update-profiles');
    });

    Route::group(['prefix' => 'sanctions'], function () {
        Route::post('/', 'SanctionController@create')->middleware('has-permission:create-sanctions');
        Route::put('/{id}', 'SanctionController@update')->middleware('has-permission:update-sanctions');
        Route::get('/', 'SanctionController@get')->middleware('has-permission:read-sanctions');
        Route::get('/{id}', 'SanctionController@find')->middleware('has-permission:read-sanctions');
        Route::get('/sanctions_day', 'SanctionController@countSantionsDay')->middleware('has-permission:read-sanctions');
        Route::delete('/{id}', 'SanctionController@delete')->middleware('has-permission:delete-sanctions');
    });

    Route::group(['prefix' => 'careers'], function () {
        Route::get('/', 'CareerController@get')->middleware('has-permission:read-sanctions');
        Route::get('/{id}', 'CareerController@find')->middleware('has-permission:read-sanctions');
        Route::delete('/{id}', 'CareerController@delete')->middleware('has-permission:delete-sanctions');
        Route::post('/', 'CareerController@create')->middleware('has-permission:create-sanctions');
        Route::match(['put', 'post'], '/{id}', 'CareerController@update')->middleware('has-permission:update-sanctions');
    });

    Route::group(['prefix' => 'trainings'], function () {
        Route::get('/', 'TrainingController@get')->middleware('has-permission:read-trainings');
        Route::post('/', 'TrainingController@create')->middleware('has-permission:create-trainings');
        Route::post('/{id}', 'TrainingController@update')->middleware('has-permission:update-trainings');
        Route::get('/{id}', 'TrainingController@find')->middleware('has-permission:read-trainings');
        Route::delete('/{id}', 'TrainingController@delete')->middleware('has-permission:delete-trainings');
    });


    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::post('/', 'DisciplinaryTeamController@create')->middleware('has-permission:create-disciplinary-teams');
        Route::match(['put', 'post'], '/{id}', 'DisciplinaryTeamController@update')->middleware('has-permission:update-disciplinary-teams');
        Route::get('/{id}', 'DisciplinaryTeamController@find')->middleware('has-permission:read-disciplinary-teams');
        Route::get('/', 'DisciplinaryTeamController@get')->middleware('has-permission:read-disciplinary-teams');
        Route::get('/getDisciplinaryTeamsWithUsers', 'DisciplinaryTeamController@getDisciplinaryTeamWithUsers')->middleware('has-permission:read-disciplinary-teams');
        Route::delete('/{id}', 'DisciplinaryTeamController@delete')->middleware('has-permission:delete-disciplinary-teams');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/getRolesWithPermissions', 'RoleController@getRolesWithPermissions')->middleware('has-permission:read-roles');
        Route::get('/', 'RoleController@get')->middleware('has-permission:read-roles');
        Route::post('/', 'RoleController@store')->middleware('has-permission:create-roles');
        Route::post('/{id}', 'RoleController@update')->middleware('has-permission:update-roles');
        Route::delete('/{id}', 'RoleController@delete')->middleware('has-permission:delete-roles');
        Route::get('/{id}', 'RoleController@find')->middleware('has-permission:read-roles');
        Route::get('/getRolesWithPermissions', 'RoleController@getRolesWithPermissions')->middleware('has-permission:read-roles');
    });

    Route::get('/permissions', 'RoleController@getPermissions')->middleware('has-permission:update-users');
    Route::match(['patch', 'post', 'put'], '/sync_user_abilities/{id}', 'RoleController@syncAbilities')->middleware('has-permission:update-users');


    Route::group(['prefix' => 'profile_updates', 'middleware' => 'has-permission:update-users'], function () {
        Route::get('/', 'ProfileUpdateController@get');
        Route::get('{id}', 'ProfileUpdateController@find');
        Route::delete('{id}', 'ProfileUpdateController@delete');
        Route::post('/', 'ProfileUpdateController@create');
        Route::match(['post','put'], '/{id}', 'ProfileUpdateController@update');
    });


    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/{id}', 'ContactController@find')->middleware('has-permission:read-contacts');
        Route::get('/', 'ContactController@get')->middleware('has-permission:read-contacts');
        Route::delete('/{id}', 'ContactController@delete')->middleware('has-permission:delete-contacts');
        Route::post('/', 'ContactController@saveContact')->middleware('has-permission:create-contacts');
        Route::put('/{id}', 'ContactController@updateContact')->middleware('has-permission:update-contacts');
    });

    Route::group(['prefix' => 'chats', 'middleware' => 'has-permission:read-chats'], function () {
        Route::post('', 'ChatController@newMessage');
        Route::get('/discussion/{id}', 'ChatController@discussionMessage');
        Route::get('/discussion/{id}/newmessages', 'ChatController@getNewMessages');
        Route::delete('/discussion/{id}', 'ChatController@deleteDiscussion');
        Route::delete('/{id}', 'ChatController@deleteMessage');
        Route::get('/discussions/{id}', 'ChatController@getDiscussions');
    });

    Route::group(['prefix' => 'assignment_types'], function () {
        Route::post('/', 'AssignmentTypeController@create')->middleware('has-permission:create-assignment-types');
        Route::match(['post', 'put'], '/{id}', 'AssignmentTypeController@update')->middleware('has-permission:update-assignment-types');
        Route::get('/{id}', 'AssignmentTypeController@find')->middleware('has-permission:read-assignment-types');
        Route::get('/', 'AssignmentTypeController@get')->middleware('has-permission:read-assignment-types');
        Route::delete('/{id}', 'AssignmentTypeController@delete')->middleware('has-permission:delete-assignment-types');
    });


    Route::group(['prefix' => 'license_types'], function () {
        Route::get('/', 'LicenseTypeController@get')->middleware('has-permission:read-license-types');
        Route::delete('/{id}', 'LicenseTypeController@delete')->middleware('has-permission:delete-license-types');
        Route::get('/{id}', 'LicenseTypeController@find')->middleware('has-permission:read-license-types');
        Route::post('/', 'LicenseTypeController@add')->middleware('has-permission:create-license-types');
        Route::match(['post', 'put'], '/{id}', 'LicenseTypeController@update')->middleware('has-permission:update-license-types');
    });

    Route::group(['prefix' => 'licenses'], function () {
        Route::post('/','LicenseController@create')->middleware('has-permission:create-licenses');
        Route::post('/{id}','LicenseController@update')->middleware('has-permission:update-licenses');
        Route::get('/', 'LicenseController@get')->middleware('has-permission:read-licenses');
        Route::delete('{id}', 'LicenseController@delete')->middleware('has-permission:delete-licenses');
        Route::get('{id}', 'LicenseController@find')->middleware('has-permission:read-licenses');
        Route::patch('{id}/changeStatus', 'LicenseController@changeStatus')->middleware('has-permission:update-licenses');
    });


    Route::group(['prefix' => 'vacations'], function () {
        Route::get('/', 'VacationController@get')->middleware('has-permission:read-vacations');
        Route::delete('/{id}', 'VacationController@delete')->middleware('has-permission:delete-vacations');
        Route::get('/{id}', 'VacationController@find')->middleware('has-permission:read-vacations');
        Route::get('/status/{status}', 'VacationController@findByStatus')->middleware('has-permission:read-vacations');
        Route::post('/', 'VacationController@create')->middleware('has-permission:create-vacations');
        Route::match(['post', 'put'], '/{id}', 'VacationController@update')->middleware('has-permission:update-vacations');
    });

    Route::group(['prefix' => 'submissions'], function () {
        Route::get('/', 'SubmissionController@get')->middleware('has-permission:read-submissions');
        Route::delete('/{id}', 'SubmissionController@delete')->middleware('has-permission:delete-submissions');
        Route::get('/{id}', 'SubmissionController@find')->middleware('has-permission:read-submissions');
        Route::match(['put', 'post'],'/', 'SubmissionController@create')->middleware('has-permission:create-submissions');
        Route::match(['put', 'post'], '/{id}', 'SubmissionController@update')->middleware('has-permission:update-submissions');
    });


    Route::group(['prefix' => 'careers'], function () {
        Route::post('/', 'CareerController@create')->middleware('has-permission:create-careers');
        Route::put('/{id}', 'CareerController@update')->middleware('has-permission:update-careers');
        Route::get('/', 'CareerController@get')->middleware('has-permission:read-careers');
        Route::delete('/{id}', 'CareerController@delete')->middleware('has-permission:delete-careers');
        Route::get('/{id}', 'CareerController@find')->middleware('has-permission:read-careers');
    });

    Route::group(['prefix' => 'blog_comments'], function () {
        Route::post('/', 'BlogCommentController@create')->middleware('has-permission:create-blog-comments');
        Route::delete('/{id}', 'BlogCommentController@delete')->middleware('has-permission:delete-blog-comments');
    });

    Route::group(['prefix' => 'user_note_criteria', 'middleware' => 'has-permission:update-users'], function () {
        Route::post('/', 'UserNoteCriteriaController@save');
        Route::match(['post', 'put'], '/{id}', 'UserNoteCriteriaController@update');
        Route::get('/', 'UserNoteCriteriaController@get');
        Route::get('/{id}', 'UserNoteCriteriaController@find');
        Route::delete('/{id}', 'UserNoteCriteriaController@delete');
    });
    Route::group(['prefix' => 'cities'], function () {
        Route::get('/', 'UserController@getCities');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@get')->middleware('has-permission:read-settings');
        Route::delete('/{id}', 'SettingController@delete')->middleware('has-permission:delete-settings');
        Route::get('/{id}', 'SettingController@find')->middleware('has-permission:read-settings');
        Route::post('/', 'SettingController@create')->middleware('has-permission:create-settings');
        Route::match(['put', 'post'], '/{id}', 'SettingController@update')->middleware('has-permission:update-settings');
    });

    Route::group(['prefix' => 'statistics', 'middleware' => 'has-permission:read-statistics'], function () {
        Route::get('/career/{id}', 'StatitisqueController@getDataSetUser');
        Route::get('/prosituation/{id}', 'StatitisqueController@getDataProSituationUser');
        Route::get('/assignment_by_month', 'StatitisqueController@getAssignByMonth');
    });
    Route::group(['prefix' => 'cities'], function () {
        Route::get('/', 'UserController@getCities');
    });
    Route::get('cities', 'CityAndCountryController@cities');
});
