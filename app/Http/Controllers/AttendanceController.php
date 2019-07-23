<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Common;
use App\Models\AtWork;
use App\Models\Emp;
use DB;
use Auth;

class AttendanceController extends Controller
{
    public function index($rqdate='',$rqgrp=0,$rqchk=0) {
        if (! $rqdate) $rqdate = date('Y-m-d');
        $empgrps = Common::EmpGroup()->get();
        $wks = AtWork::where('w_date',$rqdate)
            ->whereHas('emp', function($q) use ($rqgrp) {
                if($rqgrp > 0) {
                    $q->where('cm', $rqgrp);
                }
            })
        ->orderBy('tin')->orderBy('tout')->orderBy('w_id')->get();
        $blades = array('attns.daily','attns.check1','attns.check2');
        return view($blades[$rqchk],['rqdate'=>$rqdate,'rqgrp'=>$rqgrp,'empgrps'=>$empgrps,'wks'=>$wks]);
    }
    public function empindex($rqmth=0, $rqemp=0) {
        if (! $rqmth) $rqmth = date('Ym');
        $yr = substr($rqmth,0,4)*1;
        if (! $rqemp) $rqemp = Auth::user()->empid;
        $emp = Emp::find($rqemp);
        $wf = ($emp->cm ?? 0 > 3000);
        $col = ($wf ? 'wfo' : 'ofo');

        // $sql = "SELECT ". $col . " as id, date_format(cldate, '%F, %Y') as name,"
        //     . " min(cldate) as fromdate, max(cldate) as todate" 
        //     . " FROM hctcal WHERE " 
        //     . $col . " BETWEEN " . $yr . "01 AND ". $yr . "12"
        //     . " AND " . $col . "<" . date('Ym') 
        //     . " GROUP BY 1 ORDER BY 1 DESC;";
        // $mths = DB::raw($sql);
        $mths = Calendar::whereBetween($col, [$yr.'01', $yr.'12'])
            ->where('cldate', '<', date('Y-m-d'))
            ->selectRaw($col." as id, date_format(concat($col,'01'),'%M, %Y') as name")
            ->groupBy($col)->orderBy($col, 'desc')->get();
        
        $mth = Calendar::where($col,$rqmth)
            ->selectRaw('min(cldate) as fromdate, max(cldate) as todate')
            ->groupby($col)->first();
        $wks = AtWork::where('w_empid',$rqemp)
            ->whereBetween('w_date', [$mth->fromdate, $mth->todate])
            ->orderBy('w_date')->get();
        return view('attns.monthly',['rqemp'=>$rqemp,'rqmth'=>$rqmth,'emp'=>$emp,'mths'=>$mths,'wks'=>$wks]);
    }
    public function MonthSummary($empid,$year) {
        $emp = Emp::find($empid);
        return view('emps.tattendance',['year'=>$year,'emp'=>$emp])->render();
    }
}
