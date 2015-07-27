<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 10:48
 */

namespace App\Http\Controllers;


class ControllerZakaria {

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