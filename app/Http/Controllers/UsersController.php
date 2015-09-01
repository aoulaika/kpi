<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 01/09/2015
 * Time: 15:18
 */

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller{

	public function users(){
		$team=DB::table('users')
		->where('job','=','SME')
		->get();
		$languages=DB::table('languages')
		->get();
		$tools=DB::table('tools')
		->get();
		return View('managerViews.users')->with([
			'team'=>$team,
			'languages'=>$languages,
			'tools'=>$tools,
			]);
	}

	public function getUsers()
	{
		$users=DB::table('users')
		->get();
		return response()->json([
			'users' => $users
			]);
	}

	public function polarData(Request $request)
	{
		$params=$request->all();
        /*$data = [
        {
          className: 'Agent',
          axes: [
          {axis: "FCR", value: 0.8}, 
          {axis: "FCR Resolvable", value: 0.9},
          {axis: "Resolvable Missed", value: 0.4},
          {axis: "CI Usage", value: 0.76},  
          {axis: "EKMS Usage", value: 0.75}
          ]
        },
        {
          className: 'Average',
          axes: [
          {axis: "FCR", value: 0.76}, 
          {axis: "FCR Resolvable", value: 0.92},
          {axis: "Resolvable Missed", value: 0.2},
          {axis: "CI Usage", value: 0.7},  
          {axis: "EKMS Usage", value: 0.8}
          ]
        }
        ];*/
        return response()->json([
        	'params' => $params
        	]);
    }

    public function adduser(Request $request)
    {
    	$params=$request->all();
    	$id=DB::table('users')
    	->insertGetId([
    		'employe_id'=>$params['employe_id'],
    		'matricule'=>$params['matricule'],
    		'firstname'=>$params['firstname'],
    		'lastname'=>$params['lastname'],
    		'gender'=>$params['gender'],
    		'email'=>$params['email'],
    		'city'=>$params['city'],
    		'adress'=>$params['adress'],
    		'phone'=>$params['phone'],
    		'job'=>$params['job'],
    		'role'=>$params['role'],
    		'grade'=>$params['grade'],
    		'integration_date'=>$params['integration_date'],
    		'status'=>$params['status'],
    		'computer'=>$params['computer'],
    		'lilly_id'=>$params['lilly_id'],
    		'global_id'=>$params['global_id'],
    		'avaya_id'=>$params['avaya_id'],
    		'account'=>$params['account'],
    		'bcp'=>$params['bcp'],
    		'team'=>$params['team']
    		]);
    	foreach ($params['language'] as $l) {
    		DB::table('user_language')
    		->insert([
    			'user_id' => $id,
    			'language_id' => $l
    			]);
    	}

    	foreach ($params['tools'] as $tool) {
    		DB::table('user_tool')
    		->insert([
    			'user_id' => $id,
    			'tool_id' => $tool
    			]);
    	}

    	return response()->back();
    }
}