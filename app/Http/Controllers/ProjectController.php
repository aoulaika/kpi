<?php
/**
 * Created by PhpStorm.
 * User: Zakaria
 * Date: 01/09/2015
 * Time: 16:33
 */

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Illuminate\Support\Facades\View;

class ControllerProject extends Controller{

    public function project(){
        $projects = DB::table('projects')->get();
        $sub_projects = DB::table('sub_project')->get();
        $projects_extended = DB::table('projects')
            ->join('sub_project','sub_project.fk_project','=','projects.id')
            ->select('projects.id')
            ->distinct()
            ->lists('projects.id');
        return View('managerViews/project')->with([
            'projects' => $projects,
            'sub_projects'=> $sub_projects,
            'projects_extended'=> $projects_extended
        ]);
    }

    public function addProject(Request $req) {
        $params = $req->except(['_token']);
        $file = $req->file('photo');
        DB::table('projects')->insert([
            'name' => $params['name']
        ]);
        $max_id = DB::table('projects')->max('id');
        $destinationPath = 'C://wamp/www/kpi/public/img/proj-img';
        $filename = $max_id.'.png';
        $file->move($destinationPath,$filename);
        return redirect()->back()->with('success','Project was added successfully!');
    }

    public function deleteProj(Request $req) {
        $params = $req->except(['_token']);
        DB::table('projects')
            ->where('id','=',$params['id'])
            ->delete();
        return redirect()->back()->with('success','Project successfully deleted!');
    }

    public function addSubProj(Request $req) {
        $params = $req->except(['_token']);
        DB::table('sub_project')->insert([
            'name' => $params['name'],
            'fk_project' => $params['idSupProject']
        ]);
        return redirect()->back()->with('success','Sub-project was added successfully!');
    }

    public function deleteSubProj(Request $req) {
        $params = $req->except(['_token']);
        DB::table('sub_project')
            ->where('id','=',$params['id'])
            ->delete();
        return redirect()->back()->with('success','Sub-project successfully deleted!');
    }
}