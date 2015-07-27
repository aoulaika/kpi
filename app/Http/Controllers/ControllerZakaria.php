<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 10:48
 */

namespace App\Http\Controllers;


<<<<<<< HEAD
class ControllerZakaria extends Controller{
=======
class ControllerZakaria {

>>>>>>> 45cbc997fc169d46f774452a491ebb93e7de2ce0
    public function login(){
        return "hello";
        //return View('index');
    }

    public function dashboard(){
        return View('managerViews/dashboard');
    }

    public function forgot(){
        return View('forgot');
    }

} 