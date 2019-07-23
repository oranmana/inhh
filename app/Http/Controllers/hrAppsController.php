<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\Doc;
use App\Models\DocType;
use App\Models\hrRequest;
use App\Models\hrApp;
use App\Models\hrContract;
use App\Models\Emp;
use App\Models\EmpClass;
use App\Models\EmpPosition;
use App\Models\jobtitle;
use Auth;

class hrAppsController extends Controller
{
    public function index($rqid=0) {
        $rq = hrRequest::find($rqid);
        $apps = hrApp::where('rqid',$rqid)
            ->get();
        foreach($apps as $app) {
            $app->score = 0;
            for($i=1;$i<5;$i++) {
                $app->score += $app->interviewers()->avg('s'.$i);
            }
        }
        return view('hrapps.index',['rq'=>$rq, 'apps'=>$apps]);
    }
    public function getApplicant() {
        $appid = $_GET['appid'];
        $app = hrApp::find($appid);
        $app->body = '';
    }
    public function store(Request $res, $rqid) {
    }
    public function selection() {
        $appid = $_GET['appid'];
        $app = hrApp::find($appid);
        // $job    = $app->hrrq->job;
        // $dfcls  = $app->hrrq->job->pos->dfcls;
        // $pos    = $app->hrrq->job->Position;
        // $wf     = ($pos->sub > 10 && $pos->sub < 20);        
        // $ct->clsamt = EmpClass::find($dfcls->cat)->Allowance;
        // $ct->posamt = EmpPosition::find($ct->posid)->allowance($ct->cls,0);
        // $ct->jobamt = jobtitle::find($ct->jobid)->allowance;
        $epos = EmpPosition::orderBy('num')->get();
        $app->title = 'Final Selection Confirmation';
        $app->body = view('hrapps.select',['epos'=>$epos, 'app'=>$app])->render();
        return $app->toArray();
    }
    public function saveselection(Request $req) {
        $usr = Auth::user();
        // Decide to select one applicant to be recruited
        $appid = $req->input('appid');
        $app = hrApp::find($appid);
        $hrrq = $app->hrrq;
        // 2) Close the Request
        $hrrq->state = 3;
        $hrrq->save();
        
        // Automatic State

        // 3) Create Employment PP 
        $doctype=Common::find(9225);    // Employment PP
        $doc = new Doc;
        $doc->typeid = $doctype->id;
        $doc->pp = 1;
        $doc->tmid = $usr->emp->org->id;
        $doc->code = DocType::NewDoc($doc->typeid, $doc->tmid);
        $doc->name = $doctype->name . ' ('.$app->dir->name .')';
        $doc->docfrom = $usr->name;
        $doc->docto = 'President';
        $doc->ref = $app->dirid;
        $doc->state=1;
        $doc->CREATED_BY = $usr->id;
        $doc->UPDATED_BY = $usr->id;
        $doc->save();

        // 4) Create Employment Contract
        $job    = $app->hrrq->job;
        $dfcls  = $app->hrrq->job->pos->dfcls;
        $pos    = $app->hrrq->job->Position;
        $wf     = ($pos->sub > 10 && $pos->sub < 20);
        $ct = new hrContract;
        $ct->grp = 0;
        $ct->appid = $app->id;
        $ct->ppid = $doc->id;
        $ct->dirid = $app->dirid;
        $ct->jobid = $job->id;
        $ct->indate = $req->input('indate');
        $ct->todate = date('Y-m-d', strtotime('+119 day', strtotime($ct->indate)));
        $ct->cls = $req->input('cls');
        $ct->posid = $req->input('posid');
        $ct->amt = $req->input('amt');
        $ct->clsamt = $req->input('clsamt');
        $ct->posamt = $req->input('posamt');
        $ct->jobamt = $req->input('jobamt');
        if ($wf) {
            $empsign = Emp::WhoIs('HR','TM')->first();
            $wit1 = Emp::TitleIs('HRD Of')->fist();
            $wit2 = Emp::TitleIs('HR Of')->fist();
        } else {
            $empsign = Emp::WhoIs('','P')->first();
            $wit1 = Emp::WhoIs('PN','DM')->first();
            $wit2 = Emp::WhoIs('HR','TM')->first();
        }
        $ct->empsign = $empsign->id;
        $ct->empwit1 = $wit1->id;
        $ct->empwit2 = $wit2->id;
        $ct->save();

        // 1) Update Applicant Status
        foreach($hrrq->apps as $ap) {
            if ($ap->state == 8) exit;
            if ($ap->state !=9 ) {
                if ($ap->id == $appid) {
                    $ap->state=5;
                    $ap->rcid = $ct->id;
                    $ap->docid = $doc->id;
                } // Selected}
                else {
                    $ap->state=4;
                } // Unselected}
                $ap->UPDATED_BY = $usr->id;
                $ap->save();
            }
        }
        
        return redirect()->back();        
    }
}
