<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hrContract;

class hrContractController extends Controller
{
    public function index(Request $req) {
        $yr = $req->input('yr');
        $type = $req->input('type');

        if (empty($yr)) $yr = date('Y');
        if (! $type) $type = 0;
    
        $contracts = hrContract::where('state','<',9)->whereYear('indate',$yr)
            ->where('grp',$type)
            ->orderBy('indate','desc')->get();
        $yrs = hrContract::selectRaw("year(indate) as yr, count(id) as nums")
            ->groupBy('yr')->orderBy('yr','desc')->get();
        return view('contracts.index',['cons'=>$contracts,'yrs'=>$yrs,'yr'=>$yr,'type'=>$type]);
    }
    public function show($conid) {
        $con = hrContract::where('id',$conid)->first();
        return view('contracts.show',['con'=>$con]);
    }
    public function delete(Request $req) {
        // Delete Contract 
        $usrid = Auth::user()->id;
        $conid = $req->input('conid');
        $con = hrContract::find($conid);
        if ($con->empid) {
            // quit Employee
            $emp = $con->emp;
            $emp->qcode = 9;    // Contract Void
            $emp->qdate = date('Y-m-d');    // Today Quit
            $emp->save();
        }
        $app = $con->app;
            // open Job Post
            $rq = $app->hrrq;
            $rq->state = 1;
            $rq->save();

        // Close Application
        $app->state = 9;
        $app->save();

        // Void Contract
        $con->state = 10;
        $con->UPDATED_BY = $usrid;
        $con->save();
        // Goto Contract List
        return route('/hrcons');
    }
}
