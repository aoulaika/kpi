<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 10:48
 */

namespace App\Http\Controllers;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class ControllerZakaria extends Controller{

    public function login(){
        return View('index');
    }

    public function dashboard3(){
        $tickets=DB::table('fact')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();
        /* Tickets Per Product */
        $tickets_per_product=DB::table('fact')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
        ->whereNotNull('Product')
        ->select(DB::raw('count(*) as count, Product, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
        ->groupBy('Product','CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();
        /* Number of tickets Per agent */
        $tickets_per_agent=DB::table('fact')
        ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
        ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
        ->select(DB::raw('count(*) as count, agent_dim.Name,agent_dim.Id'))
        ->groupBy('agent_dim.Id')
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
        $ci_users=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, ci_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ci = ci_dim.Id GROUP BY agent_dim.Id');
        $kb_users=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'%https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count FROM fact, kb_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_kb = kb_dim.Id GROUP BY agent_dim.Id');
        $fcr_users=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        $fcr_reso_users=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        $tht=DB::select('SELECT agent_dim.Id, agent_dim.Name, AVG(Handling_time) / 60 AS tht, SEC_TO_TIME(AVG(Handling_time)) AS tht_time, IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END) / 60, 0) AS tht_password, SEC_TO_TIME(IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END), 0)) AS tht_password_time FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        /* Queries Ordered */
        $ci_users_ord=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, ci_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ci = ci_dim.Id GROUP BY agent_dim.Id ORDER BY count');
        $kb_users_ord=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'%https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count FROM fact, kb_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_kb = kb_dim.Id GROUP BY agent_dim.Id ORDER BY count');
        $fcr_users_ord=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id ORDER BY count');
        $fcr_reso_users_ord=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id ORDER BY count');
        $tickets_users_ord=DB::table('fact')
        ->join('agent_dim','agent_dim.Id','=','fact.fk_agent')
        ->select(DB::raw('agent_dim.Id, count(*) as count'))
        >groupBy('agent_dim.Id')
        ->orderBy('count');
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

        $ticket_min=$tickets_users_ord[0]->count;
        $ticket_max=$tickets_users_ord[sizeof($kb_users_ord)-1]->count;
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
        
        $ticket_sum=0;
        $tickets_users=array();
        $tickets_value=array();

        for ($i=0; $i < sizeof($kb_users_ord); $i++) {
            array_push($ci_names, $ci_users_ord[$i]->Name);
            array_push($kb_names, $kb_users_ord[$i]->Name);
            array_push($fcr_names, $fcr_users_ord[$i]->Name);
            array_push($fcr_reso_names, $fcr_reso_users_ord[$i]->Name);
            array_push($tickets_users, $tickets_users_ord[$i]->Name);
            array_push($tickets_value, (float)number_format($tickets_users_ord[$i]->count, 2, '.', ''));
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
        }

        $kb_avg = $kb_sum/sizeof($kb_users_ord);
        $ci_avg = $ci_sum/sizeof($kb_users_ord);
        $fcr_avg = $fcr_sum/sizeof($kb_users_ord);
        $fcr_reso_avg = $fcr_reso_sum/sizeof($kb_users_ord);
        $tickets_users_avg= $ticket_sum/sizeof($kb_users_ord);

        return View('managerViews.dashboard3')->with([
            'ci_users' => $ci_users,
            'kb_users' => $kb_users,
            'fcr_users' => $fcr_users,
            'ci' => $ci,
            'kb' => $kb,
            'fcr' => $fcr,
            'fcr_reso' => $fcr_reso,
            'fcr_reso_users' => $fcr_reso_users,
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
            'ticket_min'=>$ticket_min,
            'ticket_max'=>$ticket_max,
            'tickets_users_avg'=>$tickets_users_avg,
            'tickets_users'=>$tickets_users,
            'tickets_value'=>$tickets_value
            ]);
    }

    public function dashboard(Request $req){
        $params = $req->except(['_token']);
        $date = Carbon::now();
        $today = explode(' ',$date);

        $total_kb = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
        ->select(DB::raw('SUM(CASE WHEN (kb_dim.EKMS_knowledge_Id is not null and kb_dim.EKMS_knowledge_Id like \'%https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();

        $total_ci = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
        ->select(DB::raw('SUM(CASE WHEN ci_dim.name is not null THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();
        
        $total_fcr = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();

        $total_fcr_reso = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 and tickets_dim.fcr_resolvable =\'Yes\' THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();

        $kb =$total_kb[0]->count;
        $ci =$total_ci[0]->count;
        $fcr=['all'=>$total_fcr[0]->count,'resolvable'=>$total_fcr_reso[0]->count];
        
        $tickets=DB::table('fact')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();

        $priority=DB::table('fact')
        ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
        ->select(DB::raw('count(*) as y, priority as name'))
        ->groupBy('Priority')
        ->get();

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
        
        return View('managerViews/dashboard')->with([
            'kb' => $kb,
            'ci' => $ci,
            'fcr' => $fcr,
            'tickets_all' => $tickets_all,
            'priority' => $priority,
            'category' => [$categoryName,$categoryValue],
            'avg_tht' => $avg_tht,
            'countryChart'=>$countryChart
            ]);
    }

    public function forgot(){
        return View('forgot');
    }

    public function test(){
        return View('managerViews/test');
    }

    public function rangedate(Request $request)
    {
        $params = $request->except(['_token']);
        /* Number of tickets Per agent */
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
            ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'%https://knowledge.rf.lilly.com/%\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0) AS count'))
            ->groupBy('agent_dim.Id')
            ->get();

        $fcr= DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0)  AS count'))
            ->groupBy('agent_dim.Id')
            ->get();

        $fcr_reso= DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->select(DB::raw('agent_dim.Id, agent_dim.Name, IFNULL(SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\' AND time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END) * 100 /SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Created<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0  END),0) AS count'))
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

        return response()->json([
            'tickets_per_agent' => $tickets_per_agent,
            'ci_usage' => $ci_usage,
            'kb_usage' => $kb_usage,
            'fcr' => $fcr,
            'fcr_reso' => $fcr_reso,
            'tht' => $tht,
            'prc_nbr' => $prc_nbr
        ]);
    }

    public function jib(Request $request){
        $params = $request->all();
        $s=explode(' - ', $params['start_date']);
        $e=explode(' - ', $params['end_date']);
        $ticket_debut= DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->where('time_dim.Created','>',$s[0])
        ->where('time_dim.Created','<',$s[1])
        ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();
        $ticket_fin= DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->where('time_dim.Created','>',$e[0])
        ->where('time_dim.Created','<',$e[1])
        ->select(DB::raw('CreatedYear,CreatedMonth,CreatedDay,CreatedHour,CreatedMinute,CreatedSecond,count(*) as count'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();
        return response()->json([$ticket_debut,$ticket_fin]);
    }

    public function reload(Request $request)
    {
        $params=$request->all();
        $total_kb = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
        ->where('time_dim.Created','>=',$params['datedeb'])
        ->where('time_dim.Created','<=',$params['datefin'])
        ->select(DB::raw('SUM(CASE WHEN (kb_dim.EKMS_knowledge_Id is not null and kb_dim.EKMS_knowledge_Id like \'%https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0 END)/count(*)*100 AS count'))
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
        ->where('time_dim.Created','>=',$params['datedeb'])
        ->where('time_dim.Created','<=',$params['datefin'])
        ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();

        $total_fcr_reso = DB::table('fact')
        ->join('time_dim','fact.fk_time','=','time_dim.Id')
        ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
        ->where('time_dim.Created','>=',$params['datedeb'])
        ->where('time_dim.Created','<=',$params['datefin'])
        ->select(DB::raw('SUM(CASE WHEN tickets_dim.fcr_resolved !=0 and tickets_dim.fcr_resolvable =\'Yes\' THEN 1 ELSE 0 END)/count(*)*100 AS count'))
        ->get();
        
        $tickets=DB::table('fact')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->where('time_dim.Created','>=',$params['datedeb'])
        ->where('time_dim.Created','<=',$params['datefin'])
        ->select(DB::raw('count(*) as count, CreatedYear, CreatedMonth, CreatedDay, CreatedHour, CreatedMinute, CreatedSecond'))
        ->groupBy('CreatedYear','CreatedMonth','CreatedDay','CreatedHour')
        ->get();

        $priority=DB::table('fact')
        ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
        ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
        ->where('time_dim.Created','>=',$params['datedeb'])
        ->where('time_dim.Created','<=',$params['datefin'])
        ->select(DB::raw('count(*) as y, priority as name'))
        ->groupBy('Priority')
        ->get();
        
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

        $data=array(
            'kb'=>$total_kb,
            'ci'=>$total_ci,
            'fcr'=>$total_fcr,
            'fcr_reso'=>$total_fcr_reso,
            'priority'=>$priority,
            'category' => [$categoryName,$categoryValue],
            'avg_tht'=>$avg_tht,
            'ticket_all'=>$tickets_all,
            'countryChart'=>$countryChart
            );
        return response()->json($data);
    }
    public function errors()
    {
        return View('managerViews/errors');
    }
} 