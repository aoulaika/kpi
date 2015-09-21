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

	public function users() {
		$team=DB::table('users')
		->where('job','=','SME')
		->get();
		$languages=DB::table('languages')
		->get();
		$tools=DB::table('tools')
		->get();
    $projects=DB::table('projects')
    ->get();
    $sub_projects=DB::table('sub_project')
    ->get();
    return View('managerViews.users')->with([
     'team'=>$team,
     'languages'=>$languages,
     'tools'=>$tools,
     'sub_projects'=>$sub_projects,
     'projects'=>$projects
     ]);
  }

  public function signup() {
    $team=DB::table('users')
    ->where('job','=','SME')
    ->get();
    $languages=DB::table('languages')
    ->get();
    $tools=DB::table('tools')
    ->get();
    $projects=DB::table('projects')
    ->get();
    $sub_projects=DB::table('sub_project')
    ->get();
    return View('signup.signup')->with([
     'team'=>$team,
     'languages'=>$languages,
     'tools'=>$tools,
     'sub_projects'=>$sub_projects,
     'projects'=>$projects
     ]);
  }

  public function getTeamName(Request $request) {
    $params=$request->all();
    $team_name=DB::table('users')
    ->where('Id','=',$params['id'])
    ->select(DB::raw('CONCAT(lastname, \' \', firstname) as name'))
    ->first();
    return response()->json([
      'team_name' => $team_name
      ]);

  }

  public function getUsers() {
    $users=DB::table('users')
    ->join('user_project','user_id','=','users.id')
    ->select('users.*', 'user_project.project_id')
    ->get();
    $teams=DB::table('users')
    ->where('job','=','SME')
    ->select(DB::raw('Id as value, CONCAT(lastname, \' \', firstname) as text'))
    ->get();
    return response()->json([
     'users' => $users,
     'teams'=>$teams
     ]);
  }

  public function polarData(Request $request) {
    $params=$request->all();

    $ci=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
    ->select(DB::raw('SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS ci'))
    ->first()->ci;

    $kb=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->select(DB::raw('SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS kb'))
    ->first()->kb;

    $fcr=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->where('Contact_type','like','Phone')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS fcr'))
    ->first()->fcr;

    $fcr_reso=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->where('Contact_type','like','Phone')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN FCR_resolvable = \'Yes\' THEN 1 ELSE 0 END),0) AS fcr_reso'))
    ->first()->fcr_reso;

    $tht=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('sum(case when Handling_time <= 900 then 1 else 0 end)*100/count(*) as tht'))
    ->first()->tht;

    $tht_psr=DB::table('user_project')
    ->where('user_project.user_id','=',$params['id'])
    ->join('agent_dim','agent_dim.Code','=','user_project.account_id')
    ->join('fact','fact.fk_agent','=','agent_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('SUM(CASE WHEN (Handling_time <= 300 AND Closure_code = \'Password Reset\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN Closure_code = \'Password Reset\' THEN 1 else 0 END) as tht_psr'))
    ->first()->tht_psr;

    $avg_ci=DB::table('fact')
    ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
    ->select(DB::raw('SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS avg_ci'))
    ->first()->avg_ci;

    $avg_kb=DB::table('fact')
    ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->select(DB::raw('SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS avg_kb'))
    ->first()->avg_kb;

    $avg_fcr=DB::table('fact')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS avg_fcr'))
    ->first()->avg_fcr;

    $avg_fcr_reso=DB::table('fact')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN FCR_resolvable = \'Yes\' THEN 1 ELSE 0 END),0) AS avg_fcr_reso'))
    ->first()->avg_fcr_reso;

    $avg_tht=DB::table('fact')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('sum(case when Handling_time <= 900 then 1 else 0 end)*100/count(*) as avg_tht'))
    ->first()->avg_tht;

    $avg_tht_psr=DB::table('fact')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->select(DB::raw('SUM(CASE WHEN (Handling_time <= 300 AND Closure_code = \'Password Reset\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN Closure_code = \'Password Reset\' THEN 1 else 0 END) as avg_tht_psr'))
    ->first()->avg_tht_psr;
    
    $data = array(
      (object)array(
        'name'=>'FCR Resolvable',
        'target'=>90,
        'average'=>round(floatval($avg_fcr_reso),2),
        'agent'=>round(floatval($fcr_reso),2)
        ),
      (object)array(
        'name'=>'FCR',
        'target'=>60,
        'average'=>round(floatval($avg_fcr),2),
        'agent'=>round(floatval($fcr),2)
        ),
      (object)array(
        'name'=>'THT',
        'target'=>90,
        'average'=>round(floatval($avg_tht),2),
        'agent'=>round(floatval($tht),2)
        ),
      (object)array(
        'name'=>'THT Password Reset',
        'target'=>57,
        'average'=>round(floatval($avg_tht_psr),2),
        'agent'=>round(floatval($tht_psr),2)
        ),
      (object)array(
        'name'=>'EKMS Usage',
        'target'=>88.19,
        'average'=>round(floatval($avg_kb),2),
        'agent'=>round(floatval($kb),2)
        ),
      (object)array(
        'name'=>'CI Usage',
        'target'=>90,
        'average'=>round(floatval($avg_ci),2),
        'agent'=>round(floatval($ci),2)
        )
      );

    return response()->json([
      'data'=>$data
      ]);
  }

  public function adduser(Request $request) {
   $params=$request->all();
   $filename = '';
   if ($request->hasFile('picture')) {
    if ($request->file('picture')->isValid()) {
      $filename = $params['employe_id']  . '.' . $request->file('picture')->getClientOriginalExtension();
      $request->file('picture')->move('images/', $filename);
    }
  }
  $id=DB::table('users')
  ->insertGetId([
    'employe_id'=>$params['employe_id'],
    'matricule'=>$params['matricule'],
    'firstname'=>$params['firstname'],
    'lastname'=>$params['lastname'],
    'gender'=>$params['gender'],
    'email'=>$params['email'],
    'picture'=>$filename,
    'city'=>$params['city'],
    'adress'=>$params['adress'],
    'phone'=>$params['phone'],
    'job'=>$params['job'],
    'role'=>$params['role'],
    'grade'=>$params['grade'],
    'integration_date'=>$params['integration_date'],
    'status'=>$params['status'],
    'computer'=>$params['computer'],
    'global_id'=>$params['global_id'],
    'avaya_id'=>$params['avaya_id'],
    'bcp'=>$params['bcp'],
    'team'=>$params['team']
    ]);
  if (isset($params['account_id'])) {
    for ($i=0; $i < sizeof($params['account']); $i++) {
     if (!empty($params['account_id'][$i])) {
      try{
       DB::table('user_project')
       ->insert([
         'user_id' => $id,
         'project_id' => $params['account'][$i],
         'account_id' => $params['account_id'][$i]
         ]);
     }catch(\Exception $e) {}
     try{
       DB::table('user_sub_project')
       ->insert([
         'user_id' => $id,
         'sub_project_id' => $params['sub_account'][$i]
         ]);
     }catch(\Exception $e) {}
   }
 }
}
if (isset($params['language'])) {
  foreach ($params['language'] as $l) {
    DB::table('user_language')
    ->insert([
     'user_id' => $id,
     'language_id' => $l
     ]);
  }
}

if (isset($params['tools'])) {
  foreach ($params['tools'] as $tool) {
    DB::table('user_tool')
    ->insert([
     'user_id' => $id,
     'tool_id' => $tool
     ]);
  }
}
return redirect()->back();
}

public function getUserLanguages(Request $request) {
 $params=$request->all();
 $languages=DB::table('user_language')
 ->join('languages','languages.Id','=','user_language.language_id')
 ->join('users','users.Id','=','user_language.user_id')
 ->where('users.Id','=',$params['id'])
 ->lists('language_id');
 return response()->json([
  'languages'=>$languages
  ]);
}

public function getUserTools(Request $request) {
 $params=$request->all();
 $tools=DB::table('user_tool')
 ->join('tools','tools.Id','=','user_tool.tool_id')
 ->join('users','users.Id','=','user_tool.user_id')
 ->where('users.Id','=',$params['id'])
 ->lists('tool_id');
 return response()->json([
  'tools'=>$tools
  ]);
}

public function editUser(Request $request) {
 $params=$request->all();

 DB::table('users')
 ->where('Id', $params['Id'])
 ->update([$params['attr'] => $params['value']]);

 $users=DB::table('users')
 ->join('user_project','user_id','=','users.id')
 ->select('users.*', 'user_project.project_id')
 ->get();

 return response()->json([
  'users'=>$users
  ]);
}

public function editUserLanguage(Request $request) {
 $params=$request->all();
 $languages=DB::table('user_language')
 ->where('user_id','=',$params['Id'])
 ->whereIn('language_id',$params['remove'])
 ->delete();
 foreach ($params['add'] as $l) {
   DB::table('user_language')
   ->insert([
    'user_id'=>$params['Id'],
    'language_id'=>$l
    ]);
 }
 return response()->json([
  'languages'=>$languages
  ]);
}

public function editUserTools(Request $request) {
 $params=$request->all();
 $tools=DB::table('user_tool')
 ->where('user_id','=',$params['Id'])
 ->whereIn('tool_id',$params['remove'])
 ->delete();
 foreach ($params['add'] as $l) {
   DB::table('user_tool')
   ->insert([
    'user_id'=>$params['Id'],
    'tool_id'=>$l
    ]);
 }
 return response()->json([
  'tools'=>$tools
  ]);
}

public function getTools() {
  $tools=DB::table('tools')
  ->get();
  return response()->json([
    'tools'=>$tools
    ]);
}

public function getSubAccount() {
 $sub_accounts=DB::table('sub_project')
 ->get();
 return response()->json([
  'sub_accounts'=>$sub_accounts
  ]);
}

public function getAccount() {
 $accounts=DB::table('projects')
 ->get();
 return response()->json([
  'accounts'=>$accounts
  ]);
}

public function getAccounts(Request $request) {
  $params=$request->all();
  $accounts=DB::table('users')
  ->where('users.id','=',$params['id'])
  ->join('user_project','user_project.user_id','=','users.id')
  ->join('projects','projects.id','=','user_project.project_id')
  ->select('projects.*','user_project.account_id')
  ->get();
  return response()->json([
    'accounts'=>$accounts
    ]);
}


}