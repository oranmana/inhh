<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpLeave;
use App\Models\Leave;
use App\Models\LeaveRq;
use App\Http\Requests\LeaveRequestForm;

class MyLeaveController extends Controller
{
    public function index($yr=0,$empid=0,$type=0) {
        if(empty($empid)) {
          $empid = auth()->user()->empid;
        }

        if(empty($yr)) {
          $yr = date('Y');
        }
        if(empty($type)) {
          $type = 3689;
        }
                
        // $yrs = EmpLeave::OfEmp($empid)
        // ->join('hrworks as attn','id','=','w_id')
        //     ->selectRaw('year(attn.w_date) as yr')
        //     ->groupBy('yr')
        //     ->orderBy('yr','des')
        //     ->pluck('yr');
        $yrs = \App\Models\AtWork::where('w_empid',$empid)->whereHas('attn', function($q) {$q->where('lvid','>',0);})->selectRaw('year(w_date) as yr')->groupBy('yr')->orderBy('yr','desc')->pluck('yr');
        $lvs = EmpLeave::OfEmp($empid,$yr)->SumLeave()->pluck('qty','lvid');
        $leaves = Leave::where('off',0)->orderBy('par')->orderBy('num')->get();
        $n = 0;
        foreach($leaves as $lv) {
            $lvid = $lv->id;
            $qty = $lvs[$lvid] ?? 0;
            $lv['lvqty'] = $qty;
        };
        return view('myleave.main', compact(['yr','empid','type','leaves','yrs'])); 
    }

    public function store(Request $req) {
      $lv = LeaveRq::where('empid', $req->input('empid'))
        ->whereBetween('fdate', [$req->input('fromdate'), $req->input('todate')] )
        ->count();
      if($lv == 0) {
        $usrid = auth()->user()->id;
        $lv = new LeaveRq;
        $lv->empid = $req->input('empid');
        $lv->lvid = $req->input('leavetype');
        $lv->fdate = $req->input('fromdate');
          $date1 = date_create($req->input('fromdate'));
          $date2 = date_create($req->input('fromdate'));
          $datediff = date_diff($date1, $date2);
          $num = $datediff->format('%d');
        $lv->num = $num+1;
        $lv->rem = $req->input('reason');
        // $lv->CREATED_BY = $usrid;
        // $lv->UPDATED_BY = $usrid;
        $lv->save();
      }
      return ($lv ? $lv->id : 0);
    }

}
