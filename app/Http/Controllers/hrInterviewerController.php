<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Emp;
use App\Models\jobtitle;
use App\Models\hrInterviewer;

class hrInterviewerController extends Controller
{
    public function index($jobid) {
        $hrteam = "HR";
        $emps = Emp::whereIn('jobid', jobtitle::find($jobid)->Parents)
            ->orWhereHas('org', function($query) {
                $query->where('commons.code',"HR");
            })->get()
                ->where('qcode',0)
                ->sortBy('pos.num');
        return view('interviewers.listname',['emps'=>$emps])->render();
    }
    public function create() {
        $appid = $_GET['appid'];
        $interviewers = explode(',',$_GET['emplist']);
        $usrid = Auth::user()->id;
        foreach ($interviewers as $iw) {
            $itw = new hrInterviewer;
            $itw->appid = $appid;
            $itw->empw = $iw;
            $itw->CREATED_BY = $usrid;
            $itw->UPDATED_BY = $usrid;
            $itw->save();
        }
        return redirect()->back();
//        Return Redirect::refresh();
    }
    public function update(Request $req) {
        $usrid = Auth::user()->id;
        $evid = $req->input('evname');

        $ev = hrInterviewer::find($evid);
        $ev->s1  = $req->input('sc1');
        $ev->s2  = $req->input('sc2');
        $ev->s3  = $req->input('sc3');
        $ev->s4  = $req->input('sc4');
        $ev->accept = $req->input('evdecision');
        $ev->rem = $req->input('evsrem');
        $ev->UPDATED_BY = $usrid;
        $ev->save();
    }
}
