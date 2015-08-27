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

Route::get('/users1', [
    'as' => 'users1',
    'uses' => 'ControllerZakaria@users1'
]);

Route::any('getUsers', [
    'as' => 'getUsers',
    'uses' => 'ControllerZakaria@getUsers'
]);

Route::any('polarData', [
    'as' => 'polarData',
    'uses' => 'ControllerZakaria@polarData'
]);