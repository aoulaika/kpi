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

Route::get('/', [
    'as' => 'login',
    'uses' => 'ControllerZakaria@login'
]);

Route::any('/dashboard', [
    'as' => 'dashboard',
    'uses' => 'ControllerZakaria@dashboard'
]);

Route::any('/dashboard3', [
    'as' => 'dashboard3',
    'uses' => 'ControllerZakaria@dashboard3'
]);

Route::get('/forgot', [
    'as' => 'forgot',
    'uses' => 'ControllerZakaria@forgot'
]);

Route::any('/test', [
    'as' => 'test',
    'uses' => 'ControllerZakaria@test'
]);

Route::any('/rangedate', [
    'as' => 'rangedate',
    'uses' => 'ControllerZakaria@rangedate'
]);

Route::any('reload', [
    'as' => 'reload',
    'uses' => 'ControllerZakaria@reload'
]);

Route::any('changeAgent', [
    'as' => 'changeAgent',
    'uses' => 'ControllerZakaria@changeAgent'
]);

Route::any('jib', [
    'as' => 'jib',
    'uses' => 'ControllerZakaria@jib'
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

Route::any('getUsers', [
    'as' => 'getUsers',
    'uses' => 'UsersController@getUsers'
]);

Route::any('polarData', [
    'as' => 'polarData',
    'uses' => 'UsersController@polarData'
]);

Route::post('adduser', [
    'as' => 'adduser',
    'uses' => 'UsersController@adduser'
]);

Route::get('/project', [
    'as' => 'project',
    'uses' => 'ControllerZakaria@project'
]);

Route::get('/language', [
    'as' => 'language',
    'uses' => 'LanguagesController@language'
]);

Route::get('/getLanguages', [
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
    'uses' => 'LanguagesController@editlanguage'
]);