<?php
/**
 * Created by PhpStorm.
 * User: Zakaria
 * Date: 15/09/2015
 * Time: 13:56
 */

namespace App\Http\Controllers;

use Faker\Provider\DateTime;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ErrorsController extends Controller
{
    public function errors()
    {
        /* By default only errors created last week will be displayed */
        $end = new \DateTime();
        $end->setTime(0,0,0);
        $start = clone $end;
        $start->modify('-6 day');
        $arrayError = DB::table('fact')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('error_type','=','FCR')
            ->distinct()
            ->lists('fk_fact');

        $post_fcr = DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('contact_dim', 'fact.fk_contact', '=', 'contact_dim.Id')
            ->where('Contact_type','like','Phone')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('FCR_resolved', '=', '0')
            ->where('FCR_resolvable', '=', 'Yes')
            ->whereNotIn('fact.Id', $arrayError)
            ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,\'FCR\' as error_type,\'\' as rca_ag_comment,\'\' as action,\'\' as remarks,0 as accounted,0 as checked'));

        $fcr = DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('error_type','=','FCR')
            ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,errors.error_type as error_type,errors.rca_ag_comment as rca_ag_comment,errors.action as action,errors.remarks as remarks,errors.accounted as accounted,errors.checked as checked'))
            ->union($post_fcr)
            ->get();

        $arrayError = DB::table('fact')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('error_type','=','KB')
            ->distinct()
            ->lists('fk_fact');

        $post_kb = DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('kb_dim', 'kb_dim.Id', '=', 'fact.fk_kb')
            ->where('Category','not like','Service Catalog')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
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
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('error_type','=','KB')
            ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,errors.error_type as error_type,errors.rca_ag_comment as rca_ag_comment,errors.action as action,errors.remarks as remarks,errors.accounted as accounted,errors.checked as checked'))
            ->union($post_kb)
            ->get();

        $arrayError = DB::table('fact')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('errors', 'errors.fk_fact', '=', 'fact.Id')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->where('error_type','=','CI')
            ->distinct()
            ->lists('fk_fact');

        $post_ci = DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
            ->whereNull('ci_dim.name')
            ->whereNotIn('fact.Id',$arrayError)
            ->select(DB::raw('fact.Id,tickets_dim.Number,time_dim.Created,tickets_dim.Short_Description,agent_dim.Code,agent_dim.Name,SEC_TO_TIME(fact.Handling_time) as hdl_time,\'CI\' as error_type,\'\' as rca_ag_comment,\'\' as action,\'\' as remarks,0 as accounted,0 as checked'));

        $ci = DB::table('fact')
            ->join('agent_dim', 'agent_dim.Id', '=', 'fact.fk_agent')
            ->join('time_dim', 'time_dim.Id', '=', 'fact.fk_time')
            ->join('tickets_dim', 'tickets_dim.Id', '=', 'fact.fk_ticket')
            ->join('errors','errors.fk_fact','=','fact.Id')
            ->join('ci_dim', 'ci_dim.Id', '=', 'fact.fk_ci')
            ->where('Created','>=', $start->format('Y-m-d'))
            ->where('Created','<=', $end->format('Y-m-d'))
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
}