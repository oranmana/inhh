<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Emp;
use App\Models\Common;
use App\Models\Country;
use App\Models\Dir;
use App\Models\PayrollEmp;

class EmpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($quit=0,$cm=0,$searchtxt=0)
    {
//        $searchtxt = (isset($_GET['searchtext']) && $_GET['searchtext'] ? $_GET['searchtext'] : 0);
        $emps = Emp::join('commons as dborg','emps.orgid','=','dborg.id')
            ->join('commons as dbjob','emps.jobid','=','dbjob.id')
            ->where('qcode',($quit?'>':'='),0)
            ->where('cm',($cm?'=':'>'),($cm?$cm:0))
            ->where(function($q) use ($searchtxt) {
                if( strlen($searchtxt) > 1 ) {
                    $q->whereRaw("concat(emps.name,',',ifnull(emps.thname,'')) like convert('%". $searchtxt . "%' using utf8)");
                }
            })
            ->select('emps.id','emps.name','emps.thname','emps.indate','emps.tel','dbjob.name as jobname', 'dborg.code as orgcode')
            ->orderBy('indate', 'desc')
            ->paginate(20);

            return view('emps.index',['emps'=> $emps,'quit'=>$quit,'cm'=>$cm, 'searchtxt'=>$searchtxt]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // hrContract Confirmation on selected applicant show up on the first working date
        $usrid = Auth::user()->id;
        $conid = $req->input('conid');
        $con = hrContract::find($conid);
        $dir = $con->dir;
        $app = $con->app;
        $hrrq = $app->hrrq;
        $job = $hrrq->job;

        $emp = new Emp;
        $emp->dirid = $dir->id;
        $emp->empcode = nextcode('emp',$con->indate);
        $emp->cm = ($job->num > 10 ? 3186 : 554);
        $emp->nm = $dir->nm;
        $emp->name = $dir->name;
        $emp->thname = $dir->tname;
        $emp->indate = $con->indate;
        $emp->retireage = 55;
        $emp->cls = $con->cls;
        $emp->posid = $con->posid;
        $emp->jobid = $con->jobid;
        $emp->ccid = $job->erp;
        $emp->CREATED_BY = $usrid;
        $emp->UPDATED_BY = $usrid;
        $emp->save();

        // Promotion
        $prom = new EmpPromotion;
        $prom->empid = $emp->id;
        $prom->indate = $emp->indate;
        $prom->on = 1;
        $prom->cls = $emp->cls;
        $prom->posid = $emp->posid;
        $prom->jobid = $emp->jobid;
        $prom->rem = "Employed";
        $prom->CREATED_BY = $usrid;
        $prom->UPDATED_BY = $usrid;
        $prom->save();

        // PayItems
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function show(Emp $emp)
    {
        $dir = Dir::where('id','=',$emp->dirid)->first();
        $relgs = Common::where('main',3212)->where('off',0)->get();
        $posts = Common::where('par',15)->where('off',0)->orderBy('num')->get();
        $orgs = Common::where('main',309)->where('ref','<',6)->where('off',0)->wherenotnull('erp')->orderBy('num')->get();
        $cms = DB::table('commons')->where('cat','=',532)->where('group',0)->where('off',0)
            ->orderBy('num')
            ->get();
        $edus = DB::table('commons')->where('par','=',3224)->where('off',0)
            ->orderBy('num')
            ->get();
        $pays = PayrollEmp::where('emp_id',$emp->id)
            ->join('payrolls','payroll_id','=','payrolls.id')
            ->join('payrollamts','payemp_id','=','payrollemps.id')
            ->selectRaw('year(payon) as yr, sum(if(plus,amount,0)) as income, sum(if(plus,0,amount)) as deduct,'
                . 'sum(if(item_id=3810,amount,0)) as tax, sum(if(item_id=3811,amount,0)) as ssf, sum(if(item_id=3813,amount,0)) as pvf')
            ->groupBy('yr')
            ->orderBy('yr')->get();
            
        return view('emps.form',['emp' => $emp, 'dir' => $dir, 
            'cms' => $cms, 'edus' => $edus, 'relgs'=>$relgs, 'posts'=>$posts, 'orgs'=>$orgs, 'pays'=>$pays]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Emp  $emp
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Emp  $emp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emp $emp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emp $emp)
    {
        //
    }
}
