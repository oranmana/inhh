<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\hrRequest;
use App\Models\jobtitle;
use App\Models\Education;
use App\Models\Doc;
use DB;
use Auth;

class hrRequestController extends Controller
{
    public function index(Request $req, $yr=0, $open=1) {
        if (! $yr) { $yr = date('Y'); }
        $yrs = hrRequest::selectRaw("year(CREATED_AT) as yr")
            ->groupBy('yr')
            ->orderBy('yr','desc')
            ->get();
        $rqs = hrRequest::whereYear('CREATED_AT',$yr)
            ->where('rcid',($open?'=':'>'),0)
            ->orderBy('CREATED_AT','desc')->get();
        return view('hrrequests.index', ['rqs'=>$rqs, 'yrs'=>$yrs, 'yr'=>$yr, 'open'=>$open]);
    }
    public function create($jobid=0) {
        $job = jobtitle::find($jobid);
        $docs = Doc::whereIn('typeid',[9226,9766,9767])
            ->where('indate','>',date('Y-m-d', strtotime('-1 year')))
            ->orderBy('indate','desc')
            ->select('id','doccode','name','ref','indate')->get();
        $edus = Education::all();
        $jobtitle = jobtitle::all();
        $jobs = $jobtitle->filter(function($item) {
            return $item->vacantnums > 0;
        })->values();
        $html =  view('hrrequests.create', ['docs'=>$docs,'job'=>$job,'edus'=>$edus,'jobs'=>$jobs])->render();
        return $html;
    }
    public function getjob($jobid) {
        $job = jobtitle::find($jobid);
        $data = array(
            'minage'=> ($job->gl?$job->gl:25),
            'maxage'=> ($job->sfx?$job->sfx:40),
            'eduid'=> ($job->type?$job->type:3229),
            'jobdes'=> $job->des,
            'wage'=> ($job->dir?$job->dir:15000)
        );
        return $data;
    }
    public function addrq(Request $req) {
        $jobid = $req->input('jobid');
//        $doccode = $req->input('doccode');
        $docid = $req->input('docid');
        $rqdate = $req->input('rqdate');
        $minage = $req->input('minage');
        $maxage = $req->input('maxage');
        $eduid = $req->input('eduid');
        $jobdes = $req->input('jobdes');
        $wage = $req->input('wage');

        $usrid = Auth::user()->id;

        if ($jobid > 0) {
            $job = jobtitle::find($jobid);
            $job->gl = $minage;
            $job->sfx = $maxage;
            $job->pj = $eduid;
            $job->des = $jobdes;
            $job->dir = $wage;
            $job->save();
        }
        //$doc = Doc::where('doccode',$doccode);
            $hrrq = new hrRequest;
            $hrrq->rqdate = $rqdate;
            $hrrq->jobid = $jobid;
            $hrrq->docid = $docid;
            $hrrq->CREATED_BY = $usrid;
            $hrrq->UPDATED_BY = $usrid;
            $hrrq->save();
        return redirect('/hrrq');
    }
}
