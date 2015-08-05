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



class ControllerZakaria extends Controller{

    public function login(){
        return View('index');
    }

    public function dashboard2(){
        return View('managerViews.dashboard2');
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
        return View('managerViews/dashboard')->with([
            'kb' => $kb,
            'ci' => $ci,
            'fcr' => $fcr,
            'tickets' => $tickets,
            'priority' => $priority,
            'avg_tht' => $avg_tht
        ]);
    }

    public function forgot(){
        return View('forgot');
    }

} 