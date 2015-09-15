<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 10:48
 */

namespace App\Http\Controllers;

use Faker\Provider\DateTime;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ControllerZakaria extends Controller
{

    public function login()
    {
        return View('index');
    }

    public function dashboard3(){

        //Important
        //THE TIME IN ALL THIS QUERIES SHOULD BE SET FOR J-1
        // !!
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
        ->where('Category','not like','Service Catalog')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count'))
        ->groupBy('agent_dim.Id')
        ->get();

        $fcr_users=DB::table('fact')
        ->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
        ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->where('Contact_type','like','Phone')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count'))
        ->groupBy('agent_dim.Id')
        ->get();

        $fcr_reso_users=DB::table('fact')
        ->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
        ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->where('Contact_type','like','Phone')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN FCR_resolvable = \'Yes\' THEN 1 ELSE 0 END),0) AS count'))
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
        ->where('Category','not like','Service Catalog')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count'))
        ->groupBy('agent_dim.Id')
        ->orderBy('count','desc')
        ->get();

        $fcr_users_ord=DB::table('fact')
        ->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
        ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->where('Contact_type','like','Phone')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count'))
        ->groupBy('agent_dim.Id')
        ->orderBy('count','desc')
        ->get();

        $fcr_reso_users_ord=DB::table('fact')
        ->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
        ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->where('Contact_type','like','Phone')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / SUM(CASE WHEN FCR_resolvable = \'Yes\' THEN 1 ELSE 0 END),0) AS count'))
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
        ->where('agent_dim.Id','1')
        ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();

        /* Tickets Per Product/time for the first agent */
        $tickets_per_product=DB::table('fact')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->where('agent_dim.Id','1')
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
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->where('Contact_type','like','Phone')
        ->where('tickets_dim.fcr_resolvable','=','Yes')
        ->where('agent_dim.Id','1')
        ->count();

        $fcr_reso_missed=DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
        ->where('Contact_type','like','Phone')
        ->where('agent_dim.Id','1')
        ->where('tickets_dim.fcr_resolvable','=','Yes')
        ->where('tickets_dim.fcr_resolved','=','0')
        ->count();


        /*CSI*/
        $out=DB::table('quality')
        ->where('quality.accounted','=','NO')
        ->lists('ticket_number');

        $csi=DB::table('fact')
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->where('agent_dim.Code',$ci_users[0]->Code)
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
        ->where('csi.sla_metric','Yes')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
        ->first();

        $csi_scrub=DB::table('fact')
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->where('agent_dim.Code',$ci_users[0]->Code)
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
        ->whereNotIn('csi.ticket_number', $out)
        ->where('csi.sla_metric','Yes')
        ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
        ->first();

        /*var_dump($ci_users[0]->Name);var_dump($csi);var_dump($csi_scrub);die;*/

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
            'csi_scrub'=>$csi_scrub
            ]);
}

public function dashboard(Request $req){
    $params = $req->except(['_token']);
    $total_ticket=DB::table('fact')
    ->count();

    $total_ticket_phone=DB::table('fact')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->count();

    $kb_missed= DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->whereNull('EKMS_knowledge_Id')
    ->orWhere('EKMS_knowledge_Id','not like','https://knowledge.rf.lilly.com/%')
    ->count();

    $ci_missed = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
    ->whereNull('ci_dim.name')
    ->count();

    $fcr_missed=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('tickets_dim.fcr_resolved','=','0')
    ->count();

    $total_fcr_reso = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('SUM(CASE WHEN (tickets_dim.fcr_resolvable =\'Yes\' and tickets_dim.fcr_resolved !=0)  THEN 1 ELSE 0 END)/SUM(CASE WHEN tickets_dim.fcr_resolvable =\'Yes\' THEN 1 ELSE 0 END)*100 AS count'))
    ->get();

    $fcr_reso_total=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('tickets_dim.fcr_resolvable','=','Yes')
    ->count();

    $fcr_reso_missed=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('tickets_dim.fcr_resolvable','=','Yes')
    ->where('tickets_dim.fcr_resolved','=','0')
    ->count();

    $tickets=DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
    ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
    ->get();

    $critical=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('Priority','like','Critical')
    ->count();
    $high=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('Priority','like','High')
    ->count();
    $medium=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('Priority','like','Medium')
    ->count();
    $low=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('Priority','like','Low')
    ->count();
    $planning=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('Priority','like','Low/Planning')
    ->count();
    $priority=array(
        (object)array('y'=>$critical,'name'=>'critical'),
        (object)array('y'=>$high,'name'=>'high'),
        (object)array('y'=>$medium,'name'=>'medium'),
        (object)array('y'=>$low,'name'=>'low'),
        (object)array('y'=>$planning,'name'=>'planning'),
        );

    $category=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->whereNotNull('Category')
    ->select(DB::raw('count(*) as y, category as name'))
    ->groupBy('Category')
    ->orderBy('y', 'desc')
    ->get();
    $categoryName=array();
    foreach ($category as $key => $value) {
        array_push($categoryName, $value->name);
    }
    $categoryValue=array();
    foreach ($category as $key => $value) {
        array_push($categoryValue, $value->y);
    }
    $tht=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->select(DB::raw('avg(fact.handling_time) as avg'))
    ->get();
    $tht_password=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->where('tickets_dim.closure_code','=','Password Reset')
    ->select(DB::raw('avg(fact.handling_time) as avg'))
    ->get();
    $tab=explode(':', gmdate('H:i:s',$tht[0]->avg));
    $tab_pass=explode(':', gmdate('H:i:s',$tht_password[0]->avg));
    $avg_tht=[
    'all'=>[$tht[0]->avg/60,$tab[1].' min '.$tab[2].' sec'],
    'password'=>[$tht_password[0]->avg/60,$tab_pass[1].' min '.$tab_pass[2].' sec']
    ];
    /*End gauge Data*/
    /*Tickets Per Product*/
    $tickets_per_product=DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->whereNotNull('Product')
    ->select(DB::raw('count(*) as count, Product, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
    ->groupBy('Product','CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
    ->get();
    $tickets_product=array();
    foreach ($tickets_per_product as $value) {
        if (array_key_exists($value->Product, $tickets_product)) {
            array_push($tickets_product[$value->Product], (object)array('count' => $value->count, 'CreatedYear' => $value->CreatedYear, 'CreatedMonth' => $value->CreatedMonth, 'CreatedDay' => $value->CreatedDay, 'CreatedHour' => $value->CreatedHour, 'CreatedMinute' => $value->CreatedMinute, 'CreatedSecond' => $value->CreatedSecond));
        } else {
            $tickets_product[$value->Product] = [array('count' => $value->count, 'CreatedYear' => $value->CreatedYear, 'CreatedMonth' => $value->CreatedMonth, 'CreatedDay' => $value->CreatedDay, 'CreatedHour' => $value->CreatedHour, 'CreatedMinute' => $value->CreatedMinute, 'CreatedSecond' => $value->CreatedSecond)];
        }
    }
    $end2 = new \DateTime();
    $end2->setTime(0,0,0);
    $begin2 = clone $end2;
    $begin2->modify('-6 day');
    $end1 = clone $begin2;
    $end1->modify('-1 day');
    $begin1 = clone $end1;
    $begin1->modify('-6 day');

    $times = [[$begin1->format('Y-m-d'),$end1->format('Y-m-d')],[$begin2->format('Y-m-d'),$end2->format('Y-m-d')]];

    for($i=0;$i<sizeof($times);$i++){
        $intervals[$i] = DB::table('fact')
        ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
        ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
        ->where('time_dim.Created', '>=', $times[$i][0])
        ->where('time_dim.Created', '<=', $times[$i][1])
        ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
        ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
        ->get();
    }

    $tickets_all=[
    'all'=>$tickets,
    'product'=>$tickets_product
    ];

    $country=DB::table('fact')
    ->join('geography','geography.Id','=','fact.fk_geography')
    ->whereNotNull('geography.country_code')
    ->select(DB::raw('count(*) as count,geography.country_code,geography.country_name'))
    ->groupBy('geography.country_code')
    ->get();

    $csi_country=DB::table('fact')
    ->join('geography','geography.Id','=','fact.fk_geography')
    ->whereNotNull('geography.country_code')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
    ->select(DB::raw('AVG(csi.rate) as count,geography.country_code,geography.country_name'))
    ->groupBy('geography.country_code')
    ->get();

    $csi_map=array();
    foreach ($csi_country as $key => $value) {
        array_push($csi_map, (object)array(
            'code'=>$value->country_code,
            'value'=>$value->count,
            'name'=>$value->country_name
            ));
    }

    $csi_rate=DB::table('csi')
    ->where('csi.sla_metric','=','Yes')
    ->join('tickets_dim','tickets_dim.Number','=','csi.ticket_number')
    ->select(DB::raw('FORMAT(avg(csi.rate),2) as rate'))
    ->first()->rate;

    $out=DB::table('quality')
    ->where('quality.accounted','=','NO')
    ->lists('ticket_number');

    $csi_rate_quality=DB::table('csi')
    ->where('csi.sla_metric','=','Yes')
    ->join('tickets_dim','tickets_dim.Number','=','csi.ticket_number')
    ->whereNotIn('csi.ticket_number', $out)
    ->select(DB::raw('FORMAT(avg(csi.rate),2) as rate'))
    ->first()->rate;

    $countryChart=array();
    foreach ($country as $key => $value) {
        array_push($countryChart, (object)array(
            'code'=>$value->country_code,
            'value'=>$value->count,
            'name'=>$value->country_name
            ));
    }

    $begin = DB::table('time_dim')->min('Created');
    $begin_exp = explode('-',$begin);
    $begin_inv = $begin_exp[1].'-'.$begin_exp[2].'-'.$begin_exp[0];
    $end = (new \Datetime())->format('Y-m-d');

    return View('managerViews/dashboard')->with([
        'total_ticket'=>$total_ticket,
        'kb_missed'=>$kb_missed,
        'ci_missed'=>$ci_missed,
        'fcr_missed'=>$fcr_missed,
        'fcr_reso_total'=>$fcr_reso_total,
        'fcr_reso_missed'=>$fcr_reso_missed,
        'tickets_all' => $tickets_all,
        'priority' => $priority,
        'category' => [$categoryName,$categoryValue],
        'avg_tht' => $avg_tht,
        'countryChart'=>$countryChart,
        'total_ticket_phone'=>$total_ticket_phone,
        'critical'=>$critical,
        'high'=>$high,
        'medium'=>$medium,
        'low'=>$low,
        'planning'=>$planning,
        'intervals'=> $intervals,
        'times'=> $times,
        'begin'=> $begin,
        'begin_inv'=> $begin_inv,
        'end'=> $end,
        'csi_map'=> $csi_map,
        'csi_rate'=> $csi_rate,
        'csi_rate_quality'=> $csi_rate_quality
        ]);
}

public function forgot()
{
    return View('forgot');
}

public function test()
{
    return View('managerViews/test');
}

public function rangedate(Request $request)
{
    $params = $request->except(['_token']);
    /* Queries for all users */
    /* Number of tickets Per agent */

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
    ->where('Category','not like','Service Catalog')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0) AS count'))
    ->groupBy('agent_dim.Id')
    ->get();

    $fcr= DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0)  AS count'))
    ->groupBy('agent_dim.Id')
    ->get();

    $fcr_reso= DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\' AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0  END),0) AS count'))
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
    ->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'https://knowledge.rf.lilly.com/%\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
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
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name,SUM(CASE WHEN (FCR_resolved = 1 AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END)*100/SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count'))
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
    ->where('Contact_type','like','Phone')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\' AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0  END),0) AS count'))
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
    $out=DB::table('quality')
    ->where('quality.accounted','=','NO')
    ->lists('ticket_number');

    $csi=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->where('agent_dim.Id',$params['agent_id'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
    ->where('csi.sla_metric','Yes')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
    ->first();

    $csi_scrub=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->where('agent_dim.Id',$params['agent_id'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->whereNotIn('tickets_dim.Number', $out)
    ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
    ->where('csi.sla_metric','Yes')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
    ->first();

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
        'csi_scrub'=>$csi_scrub
        ]);
}

public function jib(Request $request)
{
    $params = $request->all();
    $s = explode(' - ', $params['start_date']);
    $e = explode(' - ', $params['end_date']);
    $ticket_debut = DB::table('fact')
    ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->where('product','=',$params['product'])
    ->where('time_dim.Created', '>=', $s[0])
    ->where('time_dim.Created', '<=', $s[1])
    ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
    ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
    ->get();
    $ticket_fin = DB::table('fact')
    ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->where('product','=',$params['product'])
    ->where('time_dim.Created', '>=', $e[0])
    ->where('time_dim.Created', '<=', $e[1])
    ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
    ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
    ->get();
    return response()->json([$ticket_debut, $ticket_fin]);
}

public function reload(Request $request)
{
    $params=$request->all();
    $total_kb = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Category','not like','Service Catalog')
    ->select(DB::raw('SUM(CASE WHEN (kb_dim.EKMS_knowledge_Id is not null and kb_dim.EKMS_knowledge_Id like \'https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0 END)/count(*)*100 AS count'))
    ->get();
    $total_ci = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('SUM(CASE WHEN ci_dim.name is not null THEN 1 ELSE 0 END)/count(*)*100 AS count'))
    ->get();

    $total_fcr = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 THEN 1 ELSE 0 END)/count(*)*100 AS count'))
    ->get();

    $total_fcr_reso = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 and tickets_dim.fcr_resolvable =\'Yes\' THEN 1 ELSE 0 END)/SUM(CASE WHEN tickets_dim.fcr_resolvable =\'Yes\' THEN 1 ELSE 0 END)*100 AS count'))
    ->get();

    $tickets=DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
    ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
    ->get();

    $critical=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Priority','like','Critical')
    ->count();
    $high=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Priority','like','High')
    ->count();
    $medium=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Priority','like','Medium')
    ->count();
    $low=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Priority','like','Low')
    ->count();
    $planning=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('Priority','like','Low/Planning')
    ->count();
    $priority=array(
        (object)array('y'=>$critical,'name'=>'critical'),
        (object)array('y'=>$high,'name'=>'high'),
        (object)array('y'=>$medium,'name'=>'medium'),
        (object)array('y'=>$low,'name'=>'low'),
        (object)array('y'=>$planning,'name'=>'planning'),
        );

    $category=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->whereNotNull('Category')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('count(*) as y, category as name'))
    ->groupBy('Category')
    ->orderBy('y', 'desc')
    ->get();
    $categoryName=array();
    foreach ($category as $key => $value) {
        array_push($categoryName, $value->name);
    }
    $categoryValue=array();
    foreach ($category as $key => $value) {
        array_push($categoryValue, $value->y);
    }
    $tht=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->select(DB::raw('avg(fact.handling_time) as avg'))
    ->get();

    $tht_password=DB::table('fact')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('tickets_dim.closure_code','=','Password Reset')
    ->select(DB::raw('avg(fact.handling_time) as avg'))
    ->get();

    $tab=explode(':', gmdate('H:i:s',$tht[0]->avg));
    $tab_pass=explode(':', gmdate('H:i:s',$tht_password[0]->avg));
    $avg_tht=[
    'all'=>[$tht[0]->avg/60,$tab[1].' min '.$tab[2].' sec'],
    'password'=>[$tht_password[0]->avg/60,$tab_pass[1].' min '.$tab_pass[2].' sec']
    ];

    $tickets_per_product=DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
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

    $country=DB::table('fact')
    ->join('geography','geography.Id','=','fact.fk_geography')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->whereNotNull('geography.country_code')
    ->select(DB::raw('count(*) as count,geography.country_code,geography.country_name'))
    ->groupBy('geography.country_code')
    ->get();
    $countryChart=array();
    foreach ($country as $key => $value) {
        array_push($countryChart, (object)array(
            'code'=>$value->country_code,
            'value'=>$value->count,
            'name'=>$value->country_name
            ));
    }
    /*end missed*/
    $total_ticket=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->count();
    $total_ticket_phone=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->count();
    $kb_missed= DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
    ->join('tickets_dim','fact.fk_ticket', '=', 'tickets_dim.Id')
    ->where('Category','not like','Service Catalog')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where(function ($query) {
        $query->whereNull('EKMS_knowledge_Id')
        ->orWhere('EKMS_knowledge_Id','not like','https://knowledge.rf.lilly.com/%');
    })
    ->count();
    $ci_missed = DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->whereNull('ci_dim.name')
    ->count();
    $fcr_missed=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('tickets_dim.fcr_resolved','=','0')
    ->count();
    $fcr_reso_total=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('tickets_dim.fcr_resolvable','=','Yes')
    ->count();
    $fcr_reso_missed=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->where('tickets_dim.fcr_resolvable','=','Yes')
    ->where('tickets_dim.fcr_resolved','=','0')
    ->count();
    /*missed*/

    $csi_country=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('geography','geography.Id','=','fact.fk_geography')
    ->whereNotNull('geography.country_code')
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
    ->select(DB::raw('AVG(csi.rate) as count,geography.country_code,geography.country_name'))
    ->groupBy('geography.country_code')
    ->get();

    $csi_map=array();
    foreach ($csi_country as $key => $value) {
        array_push($csi_map, (object)array(
            'code'=>$value->country_code,
            'value'=>$value->count,
            'name'=>$value->country_name
            ));
    }

    $csi_rate=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
    ->where('csi.sla_metric','=','Yes')
    ->select(DB::raw('FORMAT(avg(csi.rate),2) as rate'))
    ->first()->rate;

    $out=DB::table('quality')
    ->where('quality.accounted','=','NO')
    ->lists('ticket_number');

    $csi_rate_quality=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('csi', 'tickets_dim.Number', '=', 'csi.ticket_number')
    ->where('csi.sla_metric','=','Yes')
    ->whereNotIn('csi.ticket_number', $out)
    ->select(DB::raw('FORMAT(avg(csi.rate),2) as rate'))
    ->first()->rate;

    $data=array(
        'kb'=>$total_kb,
        'ci'=>$total_ci,
        'fcr'=>$total_fcr,
        'fcr_reso'=>$total_fcr_reso,
        'priority'=>$priority,
        'category' => [$categoryName,$categoryValue],
        'avg_tht'=>$avg_tht,
        'ticket_all'=>$tickets_all,
        'total_ticket'=>$total_ticket,
        'kb_missed'=>$kb_missed,
        'ci_missed'=>$ci_missed,
        'fcr_missed'=>$fcr_missed,
        'fcr_reso_total'=>$fcr_reso_total,
        'fcr_reso_missed'=>$fcr_reso_missed,
        'countryChart'=>$countryChart,
        'total_ticket_phone' => $total_ticket_phone,
        'critical'=>$critical,
        'high'=>$high,
        'medium'=>$medium,
        'low'=>$low,
        'planning'=>$planning,
        'csi_map'=> $csi_map,
        'csi_rate'=> $csi_rate,
        'csi_rate_quality'=> $csi_rate_quality
        );
    return response()->json($data);
}

public function errors()
{
    $arrayError = DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
    ->where('Created', '2015-07-31')
    ->where('error_type','=','FCR')
    ->distinct()
    ->lists('fk_fact');

    $post_fcr = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
    ->where('Contact_type','like','Phone')
    ->where('Created', '2015-07-31')
    ->where('FCR_resolved', '=', '0')
    ->where('FCR_resolvable', '=', 'Yes')
    ->whereNotIn('fact.Id', $arrayError)
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,\'FCR\' as error_type,\'\' as rca_ag_comment,\'\' as action,\'\' as remarks,0 as accounted,0 as checked'));

    $fcr = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
    ->where('Created', '2015-07-31')
    ->where('error_type','=','FCR')
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,errors.error_type as error_type,errors.rca_ag_comment as rca_ag_comment,errors.action as action,errors.remarks as remarks,errors.accounted as accounted,errors.checked as checked'))
    ->union($post_fcr)
    ->get();

    $arrayError = DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
    ->where('Created', '2015-07-31')
    ->where('error_type','=','KB')
    ->distinct()
    ->lists('fk_fact');

    $post_kb = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->where('Category','not like','Service Catalog')
    ->where('Created', '2015-07-31')
    ->where(function ($query) {
        $query->whereNull('EKMS_knowledge_Id')
        ->orWhere('EKMS_knowledge_Id', 'not like', '%https://knowledge.rf.lilly.com/%');
    })
    ->whereNotIn('fact.Id', $arrayError)
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,\'KB\' as error_type,\'\' as rca_ag_comment,\'\' as action,\'\' as remarks,0 as accounted,0 as checked'));

    $kb = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
    ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
    ->where('Created', '2015-07-31')
    ->where('error_type','=','KB')
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,errors.error_type as error_type,errors.rca_ag_comment as rca_ag_comment,errors.action as action,errors.remarks as remarks,errors.accounted as accounted,errors.checked as checked'))
    ->union($post_kb)
    ->get();

    $arrayError = DB::table('fact')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
    ->where('Created', '2015-07-31')
    ->where('error_type','=','CI')
    ->distinct()
    ->lists('fk_fact');

    $post_ci = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
    ->where('Created','2015-07-31')
    ->whereNull('ci_dim.name')
    ->whereNotIn('fact.Id',$arrayError)
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,\'CI\' as error_type,\'\' as rca_ag_comment,\'\' as action,\'\' as remarks,0 as accounted,0 as checked'));

    $ci = DB::table('fact')
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
    ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
    ->join('errors','errors.fk_fact','=','fact.Id')
    ->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
    ->where('Created','2015-07-31')
    ->where('error_type','=','CI')
    ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,errors.error_type as error_type,errors.rca_ag_comment as rca_ag_comment,errors.action as action,errors.remarks as remarks,errors.accounted as accounted,errors.checked as checked'))
    ->union($post_ci)
    ->get();


    $errors = array_merge($fcr,array_merge($kb, $ci));

    return View('managerViews/errors')->with([
        'errors' => $errors
        ]);
}
public function updateErrors(Request $request)
{
    $params = $request->except(['_token']);
    try {
        DB::table('errors')->insert([
            'fk_fact' => $params['id'],
            'error_type' => $params['errorType'],
            $params['column'] => $params['newValue']
            ]);
    }catch(\Exception $e) {
        DB::table('errors')
        ->where('fk_fact', $params['id'])
        ->where('error_type',$params['errorType'])
        ->update([$params['column'] => $params['newValue']]);
    }
}

public function changeAgent(Request $request)
{
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
    $out=DB::table('quality')
    ->where('quality.accounted','=','NO')
    ->lists('ticket_number');

    $csi=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->where('agent_dim.Id',$params['agent_id'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
    ->where('csi.sla_metric','Yes')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
    ->first();

    $csi_scrub=DB::table('fact')
    ->join('time_dim','fact.fk_time','=','time_dim.Id')
    ->where('time_dim.Created','>=',$params['datedeb'])
    ->where('time_dim.Created','<=',$params['datefin'])
    ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
    ->where('agent_dim.Id',$params['agent_id'])
    ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
    ->whereNotIn('tickets_dim.Number', $out)
    ->join('csi', 'csi.ticket_number', '=', 'tickets_dim.Number')
    ->where('csi.sla_metric','Yes')
    ->select(DB::raw('agent_dim.Id, agent_dim.Name, count(*) as count, IFNULL(FORMAT(avg(csi.rate),2),0) as rate'))
    ->first();

    return response()->json([
        'tickets_all' => $tickets_all,
        'fcr_reso_total'=>$fcr_reso_total,
        'user_pic'=>$user_pic,
        'fcr_reso_missed'=>$fcr_reso_missed,
        'csi'=>$csi,
        'csi_scrub'=>$csi_scrub
        ]);
}

public function compare(){
    $times = [['2015-08-02','2015-08-08'],['2015-08-09','2015-08-15'],['2015-08-16','2015-08-22'],['2015-08-23','2015-08-29']];
    for($i=0;$i<sizeof($times);$i++){
        $values[$i] = DB::table('fact')
        ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
        ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
        ->where('product','=','activedirectory')
        ->where('time_dim.Created', '>=', $times[$i][0])
        ->where('time_dim.Created', '<=', $times[$i][1])
        ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
        ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
        ->get();
    }
    return View('managerViews/compare')->with([
        'values' => $values,
        'times' => $times
        ]);
}

public function reloadIntervals(Request $req){
    $params = $req->except(['_token']);
    for($i=0;$i<sizeof($params['dates']);$i++){
        $dates = \DateTime::createFromFormat('Y-m-d',$params['dates'][$i]);
        $dates->setTime(0,0,0);
        $dates_fin= clone $dates;
        if($params['type']=='week')
            $dates_fin->modify('+6 day');
        if($params['type']=='month')
            $dates_fin->modify('+29 day');
        if($params['product']=='all'){
            $values[$i] = DB::table('fact')
            ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
            ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
            ->where('time_dim.Created', '>=', $dates->format('Y-m-d'))
            ->where('time_dim.Created', '<=', $dates_fin->format('Y-m-d'))
            ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
            ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
            ->get();
        }
        else{
            $values[$i] = DB::table('fact')
            ->join('time_dim', 'fact.fk_time', '=', 'time_dim.Id')
            ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
            ->where('product','=',$params['product'])
            ->where('time_dim.Created', '>=', $dates->format('Y-m-d'))
            ->where('time_dim.Created', '<=', $dates_fin->format('Y-m-d'))
            ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
            ->groupBy('CreatedYear', 'CreatedMonth', 'CreatedDay', 'CreatedHour')
            ->get();
        }
        $times[$i][0] = $dates->format('Y-m-d');
        $times[$i][1] = $dates_fin->format('Y-m-d');
    }
    return response()->json([
        'values'=> $values,
        'times'=> $times
        ]);
}
}