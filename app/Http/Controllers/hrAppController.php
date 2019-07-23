<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hrApp;
use App\Models\hrInterviewer;
use App\Http\Controllers\Api\apiGetScore as Scores;

class hrAppController extends Controller
{
    public function index($appid) {
        $app = hrApp::find($appid);
//        $app->scores = new Scores($appid,$evid);

        $app->scname = array('Overall','Personality','Job Knowledge','Working Attitude','Useful Experience');
        $app->s1 = $app->interviewers->sum('s1');
        $app->s2 = $app->interviewers->sum('s2');
        $app->s3 = $app->interviewers->sum('s3');
        $app->s4 = $app->interviewers->sum('s4');
        $app->s5 = $app->interviewers->sum('s5');
        $app->sc = collect([$app->s1,$app->s2,$app->s3,$app->s4,$app->s5])->sum();
        foreach($app->interviewers as $ev) {
            $ev->sc = collect([$ev->s1,$ev->s2,$ev->s3,$ev->s4,$ev->s5])->sum();
        }
        $app->v1 = $app->interviewers->avg('s1');
        $app->v2 = $app->interviewers->avg('s2');
        $app->v3 = $app->interviewers->avg('s3');
        $app->v4 = $app->interviewers->avg('s4');
        $app->v5 = $app->interviewers->avg('s5');
        $app->vc = collect([$app->v1,$app->v2,$app->v3,$app->v4,$app->v5])->avg();

        return view('hrapp.index',['app'=>$app]);
    }
    public function saveinterview(Request $req) {
        $appid = $req->input('appid');
        $app = hrApp::find($appid);
        if ($app) {
            $app->amt = $req->input('appamt');
            $app->indate = $req->input('appindate');
            $app->rem = $req->input('apprem');
            $app->save();
        }
        //dd($req, $appid, $app);
        return redirect()->back();
    }
}
