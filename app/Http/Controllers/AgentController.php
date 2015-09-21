<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 15/09/2015
 * Time: 13:50
 */

namespace App\Http\Controllers;

use Faker\Provider\DateTime;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AgentController extends Controller {

	public function dashboard3() {
		$total_ticket=DB::table('fact')
		->count();

		/* Number of tickets Per agent */
		$tickets_per_agent=DB::table('fact')
		->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
		->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
		->select(DB::raw('count(*) as count, agent_dim.Name,agent_dim.Id'))
		->groupBy('agent_dim.Id')
		->get();

		$ci_users=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Code, agent_dim.Name, SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count'))
		->groupBy('agent_dim.Id')
		->get();

		$kb_users=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\') THEN 1 ELSE 0  END) AS count'))
		->groupBy('agent_dim.Id')
		->get();

		$fcr_users=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolved = 1) THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\') THEN 1 ELSE 0 END) AS count'))
		->groupBy('agent_dim.Id')
		->get();

		$fcr_reso_users=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) AS count'))
		->groupBy('agent_dim.Id')
		->get();

		$tht=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, AVG(Handling_time) / 60 AS tht, SEC_TO_TIME(AVG(Handling_time)) AS tht_time, IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END) / 60, 0) AS tht_password, SEC_TO_TIME(IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END), 0)) AS tht_password_time'))
		->groupBy('agent_dim.Id')
		->get();

		$tickets_users=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count'))
		->groupBy('agent_dim.Id')
		->get();
		/* Queries Ordered */

		$ci_users_ord=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count'))
		->groupBy('agent_dim.Id')
		->orderBy('count','desc')
		->get();

		$kb_users_ord=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count'))
		->groupBy('agent_dim.Id')
		->orderBy('count','desc')
		->get();

		$fcr_users_ord=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Contact_type like \'Phone\' AND Category not like \'Service Catalog\' AND FCR_resolved = 1) THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count'))
		->groupBy('agent_dim.Id')
		->orderBy('count','desc')
		->get();

		$fcr_reso_users_ord=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (Contact_type like \'Phone\' AND Category not like \'Service Catalog\' AND FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN FCR_resolvable = \'Yes\' THEN 1 ELSE 0 END),0) AS count'))
		->groupBy('agent_dim.Id')
		->orderBy('count','desc')
		->get();

		$tickets_users_ord=DB::table('fact')
		->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
		->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count'))
		->groupBy('agent_dim.Id')
		->orderBy('count','desc')
		->get();
		/* Number of Password Reset Closure tickets per agent */
		$prc_nbr = DB ::select('SELECT a.Id,a.Name,SUM(CASE WHEN t.Closure_code=\'Password Reset\' THEN 1 ELSE 0 END) as count FROM fact f,agent_dim a,tickets_dim t WHERE f.fk_agent=a.Id AND f.fk_ticket=t.Id GROUP BY a.Id');
		/* Max and Min */
		$kb_min = $kb_users_ord[0]->count;
		$kb_max = $kb_users_ord[sizeof($kb_users_ord)-1]->count;

		$ci_min = $ci_users_ord[0]->count;
		$ci_max = $ci_users_ord[sizeof($kb_users_ord)-1]->count;

		$fcr_min = $fcr_users_ord[0]->count;
		$fcr_max = $fcr_users_ord[sizeof($kb_users_ord)-1]->count;

		$fcr_reso_min = $fcr_reso_users_ord[0]->count;
		$fcr_reso_max = $fcr_reso_users_ord[sizeof($kb_users_ord)-1]->count;

		$ticket_ord_min=$tickets_users_ord[0]->count;
		$ticket_ord_max=$tickets_users_ord[sizeof($kb_users_ord)-1]->count;
		/*List of names for bar graph*/
		$kb_names=array();
		$ci_names=array();
		$fcr_names=array();
		$fcr_reso_names=array();
		$kb_sum = 0;
		$ci_sum = 0;
		$fcr_sum = 0;
		$fcr_reso_sum = 0;
		$ci=array();
		$kb=array();
		$fcr=array();
		$fcr_reso=array();

		$ticket_ord_sum=0;
		$ticket_ord_users=array();
		$ticket_ord_value=array();

		for ($i=0; $i < sizeof($kb_users_ord); $i++) {
			array_push($ci_names, $ci_users_ord[$i]->Name);
			array_push($kb_names, $kb_users_ord[$i]->Name);
			array_push($fcr_names, $fcr_users_ord[$i]->Name);
			array_push($fcr_reso_names, $fcr_reso_users_ord[$i]->Name);
			array_push($ticket_ord_users, $tickets_users_ord[$i]->Name);
			array_push($ticket_ord_value, (int)$tickets_users_ord[$i]->count);
			if ($ci_users_ord[$i]->count<50) {
				array_push($ci, (object)array(
					'y'=>(float)number_format($ci_users_ord[$i]->count, 2, '.', ''),
					'color'=>'red'
					));
			} else {
				array_push($ci, (float)number_format($ci_users_ord[$i]->count, 2, '.', ''));
			}
			if ($kb_users_ord[$i]->count<50) {
				array_push($kb, (object)array(
					'y'=>(float)number_format($kb_users_ord[$i]->count, 2, '.', ''),
					'color'=>'red'
					));
			} else {
				array_push($kb, (float)number_format($kb_users_ord[$i]->count, 2, '.', ''));
			}
			if ($fcr_users_ord[$i]->count<50) {
				array_push($fcr, (object)array(
					'y'=>(float)number_format($fcr_users_ord[$i]->count, 2, '.', ''),
					'color'=>'red'
					));
			} else {
				array_push($fcr, (float)number_format($fcr_users_ord[$i]->count, 2, '.', ''));
			}
			if ($fcr_reso_users_ord[$i]->count<50) {
				array_push($fcr_reso, (object)array(
					'y'=>(float)number_format($fcr_reso_users_ord[$i]->count, 2, '.', ''),
					'color'=>'red'
					));
			} else {
				array_push($fcr_reso, (float)number_format($fcr_reso_users_ord[$i]->count, 2, '.', ''));
			}

			/* calculating the avg at the same time */
			$kb_sum += $kb_users_ord[$i]->count;
			$ci_sum += $ci_users_ord[$i]->count;
			$fcr_sum += $fcr_users_ord[$i]->count;
			$fcr_reso_sum += $fcr_reso_users_ord[$i]->count;
			$ticket_ord_sum += $tickets_users_ord[$i]->count;
		}

		$kb_avg = $kb_sum/sizeof($kb_users_ord);
		$ci_avg = $ci_sum/sizeof($kb_users_ord);
		$fcr_avg = $fcr_sum/sizeof($kb_users_ord);
		$fcr_reso_avg = $fcr_reso_sum/sizeof($kb_users_ord);
		$ticket_ord_avg= $ticket_ord_sum/sizeof($kb_users_ord);

		/* Tickets/time for the first agent */
		$tickets=DB::table('fact')
		->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
		->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
		->where('agent_dim.Id',$ci_users[0]->Id)
		->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
		->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
		->get();

		/* Tickets Per Product/time for the first agent */
		$tickets_per_product=DB::table('fact')
		->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
		->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
		->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
		->where('agent_dim.Id',$ci_users[0]->Id)
		->whereNotNull('Product')
		->select(DB::raw('count(*) as count, Product, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
		->groupBy('Product','CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
		->get();

		$tickets_product=array();
		foreach ($tickets_per_product as $value) {
			if(array_key_exists($value->Product, $tickets_product)){
				array_push($tickets_product[$value->Product], (object)array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond));
			}else{
				$tickets_product[$value->Product]=[array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond)];
			}
		}
		$tickets_all=[
		'all'=>$tickets,
		'product'=>$tickets_product
		];

		/*resolvable missed*/
		$fcr_reso_total=DB::table('fact')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->where('Category','not like','Service Catalog')
		->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
		->where('Contact_type','like','Phone')
		->where('tickets_dim.fcr_resolvable','=','Yes')
		->where('agent_dim.Id',$ci_users[0]->Id)
		->count();

		$fcr_reso_missed=DB::table('fact')
		->join('time_dim','fact.fk_time','=','time_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->where('Category','not like','Service Catalog')
		->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
		->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
		->where('Contact_type','like','Phone')
		->where('agent_dim.Id',$ci_users[0]->Id)
		->where('tickets_dim.fcr_resolvable','=','Yes')
		->where('tickets_dim.fcr_resolved','=','0')
		->count();


		/*CSI*/

		$csi=DB::table('csi')
		->where('csi.agent_code',$ci_users[0]->Code)
		->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
		->first();

		$csi_scrub=DB::table('csi')
		->where('csi.agent_code',$ci_users[0]->Code)
		->whereNotIn('id',function($q){
			$q->select('id')->from('csi')->where('d_sat_valid','=','NO');
		})
		->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
		->first();

		/*csi tracking*/

		$csi_tracking=DB::table('csi')
		->where('csi.agent_code',$ci_users[0]->Code)
		->select(DB::raw('csi.received as date, avg(csi.rate) as value'))
		->groupBy('csi.received_year','csi.received_month','csi.received_day')
		->get();

		$csi_users=DB::table('fact')
		->join('agent_dim', 'fact.fk_agent', '=', 'agent_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
		->select(DB::raw('agent_dim.*, FORMAT(avg(csi.rate),2) as rate, count(*) as surveys'))
		->groupBy('agent_dim.Id')
		->orderBy('rate','desc')
		->get();

		$csi_users_scrub=DB::table('fact')
		->join('agent_dim', 'fact.fk_agent', '=', 'agent_dim.Id')
		->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
		->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
		->whereNotIn('csi.id',function($q){
			$q->select('id')->from('csi')->where('d_sat_valid','=','NO');
		})
		->select(DB::raw('agent_dim.*, FORMAT(avg(csi.rate),2) as rate, count(*) as surveys'))
		->groupBy('agent_dim.Id')
		->orderBy('rate','desc')
		->get();
		$users_csi=array();
		$i=1;
		foreach ($csi_users as $obj) {
			$picture=DB::table('user_project')
			->join('users','users.Id','=','user_project.user_id')
			->where('user_project.account_id','=',$obj->Code)
			->select('users.picture')
			->first();
			if ($picture) {
				$users_csi[$obj->Code]['picture']="/images/".$picture->picture;
			} else {
				$users_csi[$obj->Code]['picture']="/images/".'default-user.png';
			}

			$users_csi[$obj->Code]['number']=$i++;
			$users_csi[$obj->Code]['name']=$obj->Name;
			$users_csi[$obj->Code]['csi']=$obj->rate;
			$users_csi[$obj->Code]['csi_surveys']=$obj->surveys;
			$users_csi[$obj->Code]['csi_scrub']=$obj->rate;
			$users_csi[$obj->Code]['csi_scrub_surveys']=0;
		}
		foreach ($csi_users_scrub as $obj) {
			$users_csi[$obj->Code]['csi_scrub']=$obj->rate;
			$users_csi[$obj->Code]['csi_scrub_surveys']=$obj->surveys;
		}

		$begin = DB::table('time_dim')->min('Created');
		$end = (new \Datetime())->format('Y-m-d');

		return View('managerViews.dashboard3')->with([
			'ci_users' => $ci_users,
			'kb_users' => $kb_users,
			'fcr_users' => $fcr_users,
			'fcr_reso_users' => $fcr_reso_users,
			'ci' => $ci,
			'kb' => $kb,
			'fcr' => $fcr,
			'fcr_reso' => $fcr_reso,
			'tht' => $tht,
			'tickets_all' => $tickets_all,
			'kb_names'=> $kb_names,
			'ci_names'=> $ci_names,
			'fcr_names'=> $fcr_names,
			'fcr_reso_names'=> $fcr_reso_names,
			'kb_max' => $kb_max,
			'kb_min' => $kb_min,
			'kb_avg' => $kb_avg,
			'ci_max' => $ci_max,
			'ci_min' => $ci_min,
			'ci_avg' => $ci_avg,
			'fcr_max' => $fcr_max,
			'fcr_min' => $fcr_min,
			'fcr_avg' => $fcr_avg,
			'fcr_reso_max' => $fcr_reso_max,
			'fcr_reso_min' => $fcr_reso_min,
			'fcr_reso_avg' => $fcr_reso_avg,
			'tickets_per_agent' => $tickets_per_agent,
			'prc_nbr' => $prc_nbr,
			'tickets_users' => $tickets_users,
			'ticket_ord_min'=>$ticket_ord_min,
			'ticket_ord_max'=>$ticket_ord_max,
			'ticket_ord_avg'=>$ticket_ord_avg,
			'ticket_ord_users'=>$ticket_ord_users,
			'ticket_ord_value'=>$ticket_ord_value,
			'fcr_reso_total'=>$fcr_reso_total,
			'fcr_reso_missed'=>$fcr_reso_missed,
			'total_ticket'=>$total_ticket,
			'csi'=>$csi,
			'begin'=>$begin,
			'end'=>$end,
			'csi_scrub'=>$csi_scrub,
			'users_csi'=>$users_csi,
			'csi_tracking'=>$csi_tracking
			]);
}

public function rangedate(Request $request) {
	$params = $request->except(['_token']);

	$total_ticket=DB::table('fact')
	->join('time_dim','fact.fk_time','=','time_dim.Id')
	->where('time_dim.Created','>=',$params['datedeb'])
	->where('time_dim.Created','<=',$params['datefin'])
	->count();

	$tickets_per_agent = DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Name,agent_dim.Id,SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->get();

	$ci_usage = DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (ci_dim.Name IS NOT NULL AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0) AS count'))
	->groupBy('agent_dim.Id')
	->get();

	$kb_usage = DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) AS count'))
	->groupBy('agent_dim.Id')
	->get();

	$fcr= DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolved = 1 AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->get();

	$fcr_reso= DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolved = 1 AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN (Category not like \'Service Catalog\' AND Contact_type like \'Phone\' AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->get();

	$tht= DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, AVG(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN Handling_time ELSE 0 END) / 60 AS tht, SEC_TO_TIME(AVG(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN Handling_time ELSE 0 END)) AS tht_time, IFNULL(AVG(CASE WHEN (Closure_code = \'Password Reset\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN Handling_time ELSE 0 END) / 60, 0) AS tht_password, SEC_TO_TIME(IFNULL(AVG(CASE WHEN (Closure_code = \'Password Reset\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN Handling_time ELSE 0 END), 0)) AS tht_password_time'))
	->groupBy('agent_dim.Id')
	->get();

	$prc_nbr= DB::table('fact')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id,agent_dim.Name,SUM(CASE WHEN (tickets_dim.Closure_code=\'Password Reset\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) as count'))
	->groupBy('agent_dim.Id')
	->get();

	/* Tickets/time for the current agent */
	$tickets=DB::table('fact')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
	->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
	->get();

	/* Tickets Per Product/time for the current agent */
	$tickets_per_product=DB::table('fact')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->whereNotNull('kb_dim.Product')
	->select(DB::raw('count(*) as count, Product, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
	->groupBy('Product','CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
	->get();

	$tickets_product=array();
	foreach ($tickets_per_product as $value) {
		if(array_key_exists($value->Product, $tickets_product)){
			array_push($tickets_product[$value->Product], (object)array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond));
		}else{
			$tickets_product[$value->Product]=[array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond)];
		}
	}

	$tickets_all=[
	'all'=>$tickets,
	'product'=>$tickets_product
	];
	/*missed resolvable*/
	$fcr_reso_total=DB::table('fact')
	->join('time_dim','fact.fk_time','=','time_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->where('Category','not like','Service Catalog')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->where('Contact_type','like','Phone')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->where('tickets_dim.fcr_resolvable','=','Yes')
	->count();

	$fcr_reso_missed=DB::table('fact')
	->join('time_dim','fact.fk_time','=','time_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->where('Category','not like','Service Catalog')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->where('Contact_type','like','Phone')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->where('tickets_dim.fcr_resolvable','=','Yes')
	->where('tickets_dim.fcr_resolved','=','0')
	->count();

	$ci_users_ord=DB::table('fact')
	->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
	->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (ci_dim.Name IS NOT NULL AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->orderBy('count','desc')
	->get();
	$kb_names=array();
	$ci_ord=array();

	$kb_users_ord=DB::table('fact')
	->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
	->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (Category not like \'Service Catalog\' AND EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->orderBy('count','desc')
	->get();
	$ci_names=array();
	$kb_ord=array();

	$fcr_users_ord=DB::table('fact')
	->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
	->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (Contact_type like \'Phone\' AND Category not like \'Service Catalog\' AND FCR_resolved = 1 AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->orderBy('count','desc')
	->get();
	$fcr_names=array();
	$fcr_ord=array();

	$fcr_reso_users_ord=DB::table('fact')
	->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (Contact_type like \'Phone\' AND Category not like \'Service Catalog\' AND FCR_resolved = 1 AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\' AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0  END),0) AS count'))
	->groupBy('agent_dim.Id')
	->orderBy('count','desc')
	->get();
	$fcr_reso_names=array();
	$fcr_reso_ord=array();
	$tickets_users_ord=DB::table('fact')
	->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
	->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
	->groupBy('agent_dim.Id')
	->orderBy('count','desc')
	->get();
	$ticket_ord_users=array();
	$ticket_ord_value=array();
	for ($i=0; $i < sizeof($kb_users_ord); $i++) {
		array_push($ci_names, $ci_users_ord[$i]->Name);
		array_push($kb_names, $kb_users_ord[$i]->Name);
		array_push($fcr_names, $fcr_users_ord[$i]->Name);
		array_push($fcr_reso_names, $fcr_reso_users_ord[$i]->Name);
		array_push($ticket_ord_users, $tickets_users_ord[$i]->Name);
		array_push($ticket_ord_value, (int)$tickets_users_ord[$i]->count);
		if ($ci_users_ord[$i]->count<50) {
			array_push($ci_ord, (object)array(
				'y'=>(float)number_format($ci_users_ord[$i]->count, 2, '.', ''),
				'color'=>'red'
				));
		} else {
			array_push($ci_ord, (float)number_format($ci_users_ord[$i]->count, 2, '.', ''));
		}
		if ($kb_users_ord[$i]->count<50) {
			array_push($kb_ord, (object)array(
				'y'=>(float)number_format($kb_users_ord[$i]->count, 2, '.', ''),
				'color'=>'red'
				));
		} else {
			array_push($kb_ord, (float)number_format($kb_users_ord[$i]->count, 2, '.', ''));
		}
		if ($fcr_users_ord[$i]->count<50) {
			array_push($fcr_ord, (object)array(
				'y'=>(float)number_format($fcr_users_ord[$i]->count, 2, '.', ''),
				'color'=>'red'
				));
		} else {
			array_push($fcr_ord, (float)number_format($fcr_users_ord[$i]->count, 2, '.', ''));
		}
		if ($fcr_reso_users_ord[$i]->count<50) {
			array_push($fcr_reso_ord, (object)array(
				'y'=>(float)number_format($fcr_reso_users_ord[$i]->count, 2, '.', ''),
				'color'=>'red'
				));
		} else {
			array_push($fcr_reso_ord, (float)number_format($fcr_reso_users_ord[$i]->count, 2, '.', ''));
		}
	}
	/*CSI*/
	$code=DB::table('agent_dim')
	->where('agent_dim.Id','=',$params['agent_id'])
	->select('agent_dim.Code')
	->first()->Code;

	$csi=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
	->first();

	$csi_scrub=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->where(function($q){
		$q->where('csi.d_sat_valid', '=', 'YES');
		$q->orWhere('csi.d_sat_valid', '=', '0,0');
		$q->orWhereNull('csi.d_sat_valid');
	})
	->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
	->first();

	/*csi tracking*/

	$csi_tracking=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->select(DB::raw('csi.received as date, avg(csi.rate) as value'))
	->groupBy('csi.received_year','csi.received_month','csi.received_day')
	->get();

	$csi_users=DB::table('fact')
	->join('agent_dim', 'fact.fk_agent', '=', 'agent_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->select(DB::raw('agent_dim.*, FORMAT(avg(csi.rate),2) as rate, count(*) as surveys'))
	->groupBy('agent_dim.Id')
	->orderBy('rate','desc')
	->get();

	$csi_users_scrub=DB::table('fact')
	->join('agent_dim', 'fact.fk_agent', '=', 'agent_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->whereNotIn('csi.id',function($q){
		$q->select('id')->from('csi')->where('d_sat_valid','=','NO');
	})
	->select(DB::raw('agent_dim.*, FORMAT(avg(csi.rate),2) as rate, count(*) as surveys'))
	->groupBy('agent_dim.Id')
	->orderBy('rate','desc')
	->get();
	$users_csi=array();
	$i=1;
	foreach ($csi_users as $obj) {
		$picture=DB::table('user_project')
		->join('users','users.Id','=','user_project.user_id')
		->where('user_project.account_id','=',$obj->Code)
		->select('users.picture')
		->first();
		if ($picture) {
			$users_csi[$obj->Code]['picture']="/images/".$picture->picture;
		} else {
			$users_csi[$obj->Code]['picture']="/images/".'default-user.png';
		}

		$users_csi[$obj->Code]['number']=$i++;
		$users_csi[$obj->Code]['name']=$obj->Name;
		$users_csi[$obj->Code]['csi']=$obj->rate;
		$users_csi[$obj->Code]['csi_surveys']=$obj->surveys;
		$users_csi[$obj->Code]['csi_scrub']=$obj->rate;
		$users_csi[$obj->Code]['csi_scrub_surveys']=0;
	}
	foreach ($csi_users_scrub as $obj) {
		$users_csi[$obj->Code]['csi_scrub']=$obj->rate;
		$users_csi[$obj->Code]['csi_scrub_surveys']=$obj->surveys;
	}
	/* */
	return response()->json([
		'tickets_per_agent' => $tickets_per_agent,
		'ci_usage' => $ci_usage,
		'kb_usage' => $kb_usage,
		'fcr' => $fcr,
		'fcr_reso' => $fcr_reso,
		'tht' => $tht,
		'prc_nbr' => $prc_nbr,
		'tickets_all' => $tickets_all,
		'fcr_reso_total'=>$fcr_reso_total,
		'fcr_reso_missed'=>$fcr_reso_missed,
		'kb_names'=>$kb_names,
		'ci_ord'=>$ci_ord,
		'ci_names'=>$ci_names,
		'kb_ord'=>$kb_ord,
		'fcr_names'=>$fcr_names,
		'fcr_ord'=>$fcr_ord,
		'fcr_reso_names'=>$fcr_reso_names,
		'fcr_reso_ord'=>$fcr_reso_ord,
		'ticket_ord_users'=>$ticket_ord_users,
		'ticket_ord_value'=>$ticket_ord_value,
		'total_ticket'=>$total_ticket,
		'csi'=>$csi,
		'csi_scrub'=>$csi_scrub,
		'users_csi'=>$users_csi,
		'csi_tracking'=>$csi_tracking
		]);
}

public function changeAgent(Request $request) {
	$params=$request->all();

	$tickets=DB::table('fact')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->where('time_dim.Created','>=',$params['datedeb'])
	->where('time_dim.Created','<=',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
	->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
	->get();

	/* Tickets Per Product/time for the first agent */
	$tickets_per_product=DB::table('fact')
	->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
	->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->where('time_dim.Created','>=',$params['datedeb'])
	->where('time_dim.Created','<=',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->whereNotNull('Product')
	->select(DB::raw('count(*) as count, Product, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
	->groupBy('Product','CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
	->get();

	$tickets_product=array();
	foreach ($tickets_per_product as $value) {
		if(array_key_exists($value->Product, $tickets_product)){
			array_push($tickets_product[$value->Product], (object)array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond));
		}else{
			$tickets_product[$value->Product]=[array('count'=>$value->count,'CreatedYear'=>$value->CreatedYear,'CreatedMonth'=>$value->CreatedMonth,'CreatedDay'=>$value->CreatedDay,'CreatedHour'=>$value->CreatedHour,'CreatedMinute'=>$value->CreatedMinute,'CreatedSecond'=>$value->CreatedSecond)];
		}
	}
	$tickets_all=[
	'all'=>$tickets,
	'product'=>$tickets_product
	];
	/*missed resolvable*/
	$fcr_reso_total=DB::table('fact')
	->join('time_dim','fact.fk_time','=','time_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->where('Category','not like','Service Catalog')
	->where('Contact_type','like','Phone')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->where('tickets_dim.fcr_resolvable','=','Yes')
	->count();

	$fcr_reso_missed=DB::table('fact')
	->join('time_dim','fact.fk_time','=','time_dim.Id')
	->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
	->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
	->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
	->where('Category','not like','Service Catalog')
	->where('Contact_type','like','Phone')
	->where('time_dim.Created','>',$params['datedeb'])
	->where('time_dim.Created','<',$params['datefin'])
	->where('agent_dim.Id',$params['agent_id'])
	->where('tickets_dim.fcr_resolvable','=','Yes')
	->where('tickets_dim.fcr_resolved','=','0')
	->count();


	$tt=DB::table('agent_dim')
	->where('agent_dim.Id','=',$params['agent_id'])
	->join('user_project as up','agent_dim.Code','=','up.account_id')
	->join('users','up.user_id','=','users.Id')
	->get();
	$user_pic = 'default-user.png';
	if(count($tt)>0){
		$user_pic = $tt[0]->picture;
	}

	/*CSI*/
	$code=DB::table('agent_dim')
	->where('agent_dim.Id','=',$params['agent_id'])
	->select('agent_dim.Code')
	->first()->Code;

	$csi=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
	->first();

	$csi_scrub=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->where(function($q){
		$q->where('csi.d_sat_valid', '=', 'YES');
		$q->orWhere('csi.d_sat_valid', '=', '0,0');
		$q->orWhereNull('csi.d_sat_valid');
	})
	->select(DB::raw('count(*) as count, FORMAT(avg(csi.rate),2) as rate'))
	->first();

	/*csi tracking*/

	$csi_tracking=DB::table('csi')
	->where('csi.received','>',$params['datedeb'])
	->where('csi.received','<',$params['datefin'])
	->where('csi.agent_code',$code)
	->select(DB::raw('csi.received as date, avg(csi.rate) as value'))
	->groupBy('csi.received_year','csi.received_month','csi.received_day')
	->get();

	return response()->json([
		'tickets_all' => $tickets_all,
		'fcr_reso_total'=>$fcr_reso_total,
		'user_pic'=>$user_pic,
		'fcr_reso_missed'=>$fcr_reso_missed,
		'csi'=>$csi,
		'csi_scrub'=>$csi_scrub,
		'csi_tracking'=>$csi_tracking
		]);
}

public function reloadTrack(Request $request)
{
	$params = $request->all();
	$code=DB::table('agent_dim')
	->where('agent_dim.Id','=',$params['agent_id'])
	->select('agent_dim.Code')
	->first()->Code;
	$csi_tracking=array();
	if ($params['scrub']==0) {
		$csi_tracking=DB::table('csi')
		->where('csi.received','>',$params['debut'])
		->where('csi.received','<',$params['fin'])
		->where('csi.agent_code',$code)
		->select(DB::raw('csi.received as date, avg(csi.rate) as value'))
		->groupBy('csi.received_year','csi.received_month','csi.received_day')
		->get();
	} else {
		$csi_tracking=DB::table('csi')
		->where('csi.received','>',$params['debut'])
		->where('csi.received','<',$params['fin'])
		->where('csi.agent_code',$code)
		->where(function($q){
			$q->where('csi.d_sat_valid', '=', 'YES');
			$q->orWhere('csi.d_sat_valid', '=', '0,0');
			$q->orWhereNull('csi.d_sat_valid');
		})
		->select(DB::raw('csi.received as date, avg(csi.rate) as value'))
		->groupBy('csi.received_year','csi.received_month','csi.received_day')
		->get();
	}

	return response()->json([
		'csi_tracking'=>$csi_tracking
		]);
}
}