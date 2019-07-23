<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\jobtitle;
use App\Models\Emp;

class JobController extends Controller
{
    public function index(Request $req, $jobid=0) {
        $jobpar = jobtitle::find(9058);
        $job = jobtitle::find($jobid);
        $emps = ($jobid ? Emp::Ofjob($jobid)->isActive()
            ->with(['promotion','pos'=>function($q) {
                $q->selectRaw('*, num as positionnum') 
                    ->orderBy('positionnum');
            }])->get()->sortBy('positionnum') : 0);
        if ( $req->ajax() ) {
            return view('jobs.emplist',compact('jobpar','jobid','job','emps'))->render();
        }
        return view('jobs.main',compact('jobpar','jobid','job','emps'));
    }
    public function emplist($empid) {
        $emp = Emp::find($empid);
        $data = array();
        $data[0] = "Employee Promotion : " . $emp->name;
        $data[1] = view('jobs.empjobs', ['emp'=>$emp])->render();
        return $data;
    }
    
    public function addunder(\App\Http\Requests\addjobRequest $req) 
    {

        $org = New jobtitle;
        $org->main = 9058;
        $org->par = $req->input('parid');
        $org->code = $req->input('code');
        $org->name = $req->input('name');
        $org->tname = $req->input('tname');
        $org->cat = $req->input('cat');
        $org->erp = $req->input('erp');
        $org->sub = $req->input('sub');
        $org->type = $req->input('type');
        $org->gl = $req->input('gl');
        $org->sfx = $req->input('sfx');
        $org->pj = $req->input('pj');
        $org->dir = $req->input('dir');

        // $org->save();
        $neworg = $org->id;
        return ['newid', $neworg];
    }

    public function rename(\App\Http\Requests\addjobRequest $req) 
    {

        $old = jobtitle::find($req->input('jobid'));
        $org->code = $req->input('code');
        $org->name = $req->input('name');
        $org->tname = $req->input('tname');

        // $org->save();
        return ['renamed', $neworg];
    }

    public function relocate(Request $rq) 
    {
        $remove = ($rq->input('parid') == 0);
        $job = jobtitle::find(($rq->input('jobid')));
        $underjob = jobtitle::find($rq->input('parid'));
        
        if ($remove) {
            $job->off = 1;
        } else {
            if ($job->PositionClass > $underjob->PositionClass) {
                $job->par = $rq->input('parid');
            } else {
                return ['error','Lower Job Class, Relocation Denied'];
            }
        }

        $job->save();
        return ['message',($remove ? 'Job Title Removed' : 'Job Relocation Success')];
    }
 
}

