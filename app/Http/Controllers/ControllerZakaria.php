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

    public function dashboard2(){
        $ci_users=DB::select('SELECT agent_dim.Id, agent_dim.Name, SUM(CASE WHEN ci_dim.Name IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, ci_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ci = ci_dim.Id GROUP BY agent_dim.Id');
        $kb_users=DB::select('SELECT agent_dim.Id, SUM(CASE WHEN (EKMS_knowledge_Id IS NOT NULL AND EKMS_knowledge_Id LIKE \'%https://knowledge.rf.lilly.com/%\') THEN 1 ELSE 0  END) * 100 / COUNT(*) AS count FROM fact, kb_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_kb = kb_dim.Id GROUP BY agent_dim.Id');
        $fcr_users=DB::select('SELECT agent_dim.Id, SUM(CASE WHEN FCR_resolved = 1 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        $fcr_reso_users=DB::select('SELECT agent_dim.Id, SUM(CASE WHEN (FCR_resolved = 1 AND FCR_resolvable = \'Yes\') THEN 1 ELSE 0 END) * 100 / COUNT(*) AS count FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        $tht=DB::select('SELECT agent_dim.Id, AVG(Handling_time) / 60 AS tht, SEC_TO_TIME(AVG(Handling_time)) AS tht_time, IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END) / 60, 0) AS tht_password, SEC_TO_TIME(IFNULL(AVG(CASE WHEN Closure_code = \'Password Reset\' THEN Handling_time END), 0)) AS tht_password_time FROM fact, tickets_dim, agent_dim WHERE fact.fk_agent = agent_dim.Id AND fact.fk_ticket = tickets_dim.Id GROUP BY agent_dim.Id');
        return View('managerViews.dashboard2')->with([
            'ci_users' => $ci_users,
            'kb_users' => $kb_users,
            'fcr_users' => $fcr_users,
            'fcr_reso_users' => $fcr_reso_users,
            'tht' => $tht
        ]);
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
        /* Number of Password Reset Closure tickets per agent */
        $prc_nbr = DB ::select('SELECT a.Id,a.Name,SUM(CASE WHEN t.Closure_code=\'Password Reset\' THEN 1 ELSE 0 END) as count FROM fact f,agent_dim a,tickets_dim t WHERE f.fk_agent=a.Id AND f.fk_ticket=t.Id GROUP BY a.Id');
        /* Max and Min */
        $kb_min = $kb_users_ord[0]->count;
        $kb_max = $kb_users_ord[sizeof($kb_users_ord)-1]->count;
        $ci_min = $ci_users_ord[0]->count;
        $ci_max = $ci_users_ord[sizeof($kb_users_ord)-1]->count;
        $fcr_min = $fcr_users_ord[0]->count;
        $fcr_max = $fcr_users_ord[sizeof($kb_users_ord)-1]->count;
        /*List of names for bar graph*/
        $kb_names="'".$kb_users_ord[0]->Name."'";
        $ci_names="'".$ci_users_ord[0]->Name."'";
        $fcr_names="'".$fcr_users_ord[0]->Name."'";
        $kb_sum = $kb_users_ord[0]->count;
        $ci_sum = $ci_users_ord[0]->count;
        $fcr_sum = $fcr_users_ord[0]->count;

        for ($i=1; $i < sizeof($kb_users_ord); $i++) {
            $kb_names = $kb_names.",'".$kb_users_ord[$i]->Name."'";
            $ci_names = $ci_names.",'".$ci_users_ord[$i]->Name."'";
            $fcr_names = $fcr_names.",'".$fcr_users_ord[$i]->Name."'";
            /* calculating the avg at the same time */
            $kb_sum += $kb_users_ord[$i]->count;
            $ci_sum += $ci_users_ord[$i]->count;
            $fcr_sum += $fcr_users_ord[$i]->count;
        }

        $kb_avg = $kb_sum/sizeof($kb_users_ord);
        $ci_avg = $ci_sum/sizeof($kb_users_ord);
        $fcr_avg = $fcr_sum/sizeof($kb_users_ord);

        return View('managerViews.dashboard3')->with([
            'ci_users' => $ci_users,
            'kb_users' => $kb_users,
            'fcr_users' => $fcr_users,
            'ci_users_ord' => $ci_users_ord,
            'kb_users_ord' => $kb_users_ord,
            'fcr_users_ord' => $fcr_users_ord,
            'fcr_reso_users' => $fcr_reso_users,
            'tht' => $tht,
            'tickets_all' => $tickets_all,
            'kb_names'=> $kb_names,
            'ci_names'=> $ci_names,
            'fcr_names'=> $fcr_names,
            'kb_max' => $kb_max,
            'kb_min' => $kb_min,
            'kb_avg' => $kb_avg,
            'ci_max' => $ci_max,
            'ci_min' => $ci_min,
            'ci_avg' => $ci_avg,
            'fcr_max' => $fcr_max,
            'fcr_min' => $fcr_min,
            'fcr_avg' => $fcr_avg,
            'tickets_per_agent' => $tickets_per_agent,
            'prc_nbr' => $prc_nbr
        ]);
    }

    public function dashboard(Request $req){
        $params = $req->except(['_token']);
        $date = Carbon::now();
        $today = explode(' ',$date);
        if( !empty($params['datedeb']) && !empty($params['datefin']) && $params['datedeb']!='1900-01-01')
        {
            $total_kb = DB::table('fact')
                ->join('time_dim','fact.fk_time','=','time_dim.Id')
                ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
                ->where('kb_dim.EKMS_knowledge_Id','!=','null')
                ->where('Created','>=',$params['datedeb'])
                ->where('Closed','<=',$params['datefin'])
                ->count();
            $total_ci = DB::table('fact')
                ->join('time_dim','fact.fk_time','=','time_dim.Id')
                ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
                ->where('Created','>=',$params['datedeb'])
                ->where('Closed','<=',$params['datefin'])
                ->where('ci_dim.name','!=','null')
                ->count();
            $total_fcr = DB::table('fact')
                ->join('time_dim','fact.fk_time','=','time_dim.Id')
                ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
                ->where('Created','>=',$params['datedeb'])
                ->where('Closed','<=',$params['datefin'])
                ->where('tickets_dim.fcr_resolved','!=','0')
                ->count();
            $total_fcr_reso = DB::table('fact')
                ->join('time_dim','fact.fk_time','=','time_dim.Id')
                ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
                ->where('Created','>=',$params['datedeb'])
                ->where('Closed','<=',$params['datefin'])
                ->where('tickets_dim.fcr_resolvable','=','Yes')
                ->where('tickets_dim.fcr_resolved','!=','0')
                ->count();
            $total = DB::table('fact')
                ->join('time_dim','fact.fk_time','=','time_dim.Id')
                ->where('Created','>=',$params['datedeb'])
                ->where('Closed','<=',$params['datefin'])
                ->count();
        }
        else{
            $total_kb = DB::table('fact')
                ->join('kb_dim', 'fact.fk_kb', '=', 'kb_dim.Id')
                ->where('kb_dim.EKMS_knowledge_Id','!=','null')
                ->count();

            $total_ci = DB::table('fact')
                ->join('ci_dim', 'fact.fk_ci', '=', 'ci_dim.Id')
                ->where('ci_dim.name','!=','null')
                ->count();
            $total_fcr = DB::table('fact')
                ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
                ->where('tickets_dim.fcr_resolved','!=','0')
                ->count();
            $total_fcr_reso = DB::table('fact')
                ->join('tickets_dim', 'fact.fk_ticket', '=', 'tickets_dim.Id')
                ->where('tickets_dim.fcr_resolvable','=','Yes')
                ->where('tickets_dim.fcr_resolved','!=','0')
                ->count();
            $total = DB::table('fact')->count();
        }
        if($total!=0)
        {
            $kb = ($total_kb/$total)*100;
            $ci = ($total_ci/$total)*100;
            $fcr = [
                'all'=>($total_fcr/$total)*100,
                'resolvable'=>($total_fcr_reso/$total)*100
            ];
        }
        else{
            $kb = 0;
            $ci = 0;
            $fcr = [
                'all'=>0,
                'resolvable'=>0
            ];
        }
        /*AyoubOlk*/
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
        /*End AyoubOlk*/
        /*gauge Data*/
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
        /*Tickets Per Product*/
        /*Country*/
        $country=DB::table('fact')
            ->join('geography','geography.Id','=','fact.fk_geography')
            ->whereNotNull('geography.country_code')
            ->select(DB::raw('count(*) as count,geography.country_code,geography.country_name'))
            ->groupBy('geography.country_code')
            ->get();
        $countryChart=array();
        array_push($countryChart, ['Country', 'Popularity']);
        foreach ($country as $key => $value) {
            array_push($countryChart, [$value->country_code, $value->count]);
        }
        /*Country*/
        return View('managerViews/dashboard')->with([
            'kb' => $kb,
            'ci' => $ci,
            'fcr' => $fcr,
            'tickets_all' => $tickets_all,
            'priority' => $priority,
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
            ->select(DB::raw('SUM(CASE WHEN (time_dim.Created>=\'' . $params['datedeb'] . '\' AND time_dim.Closed<=\'' . $params['datefin'] . '\') THEN 1 ELSE 0 END) AS count, agent_dim.Name,agent_dim.Id'))
            ->groupBy('agent_dim.Id')
            ->get();
        return response()->json([
            'return' => $tickets_per_agent
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
} 