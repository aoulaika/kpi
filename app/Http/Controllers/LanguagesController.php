<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 01/09/2015
 * Time: 15:04
 */

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LanguagesController extends Controller{

	public function language(){
		return View('managerViews.languages');
	}

	public function getLanguages(){
		$languages=DB::table('languages')
		->get();
		return response()->json([
			'languages'=>$languages
			]);
	}

	public function addlanguage(Request $request){
		$params=$request->all();

		DB::table('languages')
		->insert(['name' => $params['name']]);

		$languages=DB::table('languages')
		->get();

		return response()->json([
			'languages'=>$languages
			]);
	}

	public function deletelanguage(Request $request){
		$params=$request->all();

		DB::table('languages')
		->where('Id','=',$params['id'])
		->delete();

		$languages=DB::table('languages')
		->get();

		return response()->json([
			'languages'=>$languages
			]);
	}

	public function editlanguage(Request $request){
		$params=$request->all();
		DB::table('languages')
		->where('Id', $params['id'])
		->update(['name' => $params['name']]);
		return response()->json([
			'data'=>'data'
			]);
	}
}