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

Route::any('/dashboard2', [
    'as' => 'dashboard2',
    'uses' => 'ControllerZakaria@dashboard2'
]);

Route::get('/forgot', [
    'as' => 'forgot',
    'uses' => 'ControllerZakaria@forgot'
]);

