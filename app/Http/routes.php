<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::any('/dashboard', [
    'as' => 'dashboard',
    'uses' => 'DashboardController@dashboard'
]);

Route::any('/dashboard3', [
    'as' => 'dashboard3',
    'uses' => 'AgentController@dashboard3'
]);

Route::any('/rangedate', [
    'as' => 'rangedate',
    'uses' => 'AgentController@rangedate'
]);

Route::any('reload', [
    'as' => 'reload',
    'uses' => 'DashboardController@reload'
]);

Route::any('changeAgent', [
    'as' => 'changeAgent',
    'uses' => 'AgentController@changeAgent'
]);

Route::any('errors', [
    'as' => 'errors',
    'uses' => 'ControllerZakaria@errors'
]);


Route::any('updateErrors', [
    'as' => 'updateErrors',
    'uses' => 'ControllerZakaria@updateErrors'
]);

Route::get('/users', [
    'as' => 'users',
    'uses' => 'UsersController@users'
]);

Route::get('getUsers', [
    'as' => 'getUsers',
    'uses' => 'UsersController@getUsers'
]);

Route::get('getTeams', [
    'as' => 'getTeams',
    'uses' => 'UsersController@getTeams'
]);

Route::post('getTeamName', [
    'as' => 'getTeamName',
    'uses' => 'UsersController@getTeamName'
]);

Route::post('polarData', [
    'as' => 'polarData',
    'uses' => 'UsersController@polarData'
]);

Route::post('adduser', [
    'as' => 'adduser',
    'uses' => 'UsersController@adduser'
]);

Route::post('getUserLanguages', [
    'as' => 'getUserLanguages',
    'uses' => 'UsersController@getUserLanguages'
]);

Route::post('getUserTools', [
    'as' => 'getUserTools',
    'uses' => 'UsersController@getUserTools'
]);

Route::post('editUser', [
    'as' => 'editUser',
    'uses' => 'UsersController@editUser'
]);

Route::get('getTools', [
    'as' => 'getTools',
    'uses' => 'UsersController@getTools'
]);

Route::post('editUserLanguage', [
    'as' => 'editUserLanguage',
    'uses' => 'UsersController@editUserLanguage'
]);

Route::post('editUserTools', [
    'as' => 'editUserTools',
    'uses' => 'UsersController@editUserTools'
]);

Route::get('/project', [
    'as' => 'project',
    'uses' => 'ControllerProject@project'
]);

Route::get('/language', [
    'as' => 'language',
    'uses' => 'LanguagesController@language'
]);

Route::get('getLanguages', [
    'as' => 'getLanguages',
    'uses' => 'LanguagesController@getLanguages'
]);

Route::post('addlanguage', [
    'as' => 'addlanguage',
    'uses' => 'LanguagesController@addlanguage'
]);

Route::post('deletelanguage', [
    'as' => 'deletelanguage',
    'uses' => 'LanguagesController@deletelanguage'
]);

Route::post('editlanguage', [
    'as' => 'editlanguage',
    'uses' => 'ControllerZakaria@editlanguage'
]);

Route::post('addProject', [
    'as' => 'addProject',
    'uses' => 'ControllerProject@addProject'
]);

Route::post('deleteProj', [
    'as' => 'deleteProj',
    'uses' => 'ControllerProject@deleteProj'
]);

Route::post('addSubProj', [
    'as' => 'addSubProj',
    'uses' => 'ControllerProject@addSubProj'
]);

Route::post('deleteSubProj', [
    'as' => 'deleteSubProj',
    'uses' => 'ControllerProject@deleteSubProj'
]);

Route::get('signup', [
    'as' => 'signup',
    'uses' => 'UsersController@signup'
]);


Route::get('getAccount', [
    'as' => 'getAccount',
    'uses' => 'UsersController@getAccount'
]);

Route::get('getSubAccount', [
    'as' => 'getSubAccount',
    'uses' => 'UsersController@getSubAccount'
]);

Route::get('reloadIntervals', [
    'as' => 'reloadIntervals',
    'uses' => 'DashboardController@reloadIntervals'
]);
