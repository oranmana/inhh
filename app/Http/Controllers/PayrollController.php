<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\Common;
use App\Models\Payroll;
use App\Models\PayrollEmp;
use App\Models\PayrollAmt;

class PayrollController extends Controller
{
    public function add() {
        $mth = date('Ym');
        $half = date('d') < 15;
        if ($half) {
            $cm = 3186;
        } else {
            if ( Payroll::Paid($mth,3186,0)->count() ) {
                $cm = 0;
            } else {
                if ( Payroll::Paid($mth,554,0)->count() ) {
                    $cm = 3186;
                } else {
                    if ( Payroll::Paid($mth,553,0)->count() ) {
                        $cm = 554;
                    } else {
                        $cm = 553;
                    }
                }
            }
        }
        $payon = ($cm == 0 ? 0 : PayDate($mth,$cm,$half) );
        $wfwage = strtotime('-1 month', strtotime($mth.'24'));
        $wagefrom = ($cm==0 ? 0 : 
            ($cm == 3186 ? $wfwage : strtotime($mth.'01') ) );
        $wageto = ($cm==0 ? 0 : 
            ($cm == 3186 ? ($half ? strtotime($mth.'08') : strtotime($mth.'24') )
                : strtotime(date('Ymt', $wagefrom))
            ) );
        $otfrom = $wfwage;
        $otto = strtotime($mth.'23');

        return view('payrolls.add', compact('mth', 'cm', 'half', 'payon', 'wagefrom', 'wageto', 'otfrom', 'otto'));
    }
    public function ItemSums($payid) {
        $items = PayrollAmt::ForPayId($payid)->select('plus','item_id',DB::raw('sum(amount) as amt'))
            ->with(['item'=>function($q) {
                $q->select('id','name','cat','erp','code');
            }])->groupBy(['plus','item_id'])->get()
            ->sortBy(['plus','cat','erp','code']);
        return $items;
    }

    public function PayEmp($payempid) {
        
    }
    public function index($payrollid) {
        $payroll = Payroll::find($payrollid);
        $pays = PayrollEmp::PayId($payrollid)->get();
        $payitems = $this->ItemSums($payrollid);
        return view('payroll.index',['payroll'=>$payroll, 'payitems'=>$payitems, 'pays'=>$pays]);
    }

    public function MonthSummary($empid,$year=0) {
        $pays = PayrollEmp::where('emp_id',$empid)
            ->join('payrolls','payroll_id','=','payrolls.id')
                ->where(function($q) use ($year) {if ($year) { $q->whereYear('payon',$year); } } )
            ->join('payrollamts','payemp_id','=','payrollemps.id')
            ->selectRaw( ($year ? "date_format(payon,'%Y%m')" : "year(payon)"). " as yr, sum(if(plus,amount,0)) as income, sum(if(plus,0,amount)) as deduct,"
                . 'sum(if(item_id=3810,amount,0)) as tax, sum(if(item_id=3811,amount,0)) as ssf, sum(if(item_id=3813,amount,0)) as pvf')
            ->groupBy('yr')
            ->orderBy('yr')->get();
        // if ( Request::ajax() ) {
            return view('emps.tpayroll',['pays'=>$pays,'empid'=>$empid,'year'=>$year])->render();
        // } else {
            // return view('emps.tpayroll',['pay'=>$pays,'empid'=>$empid,'period'=>$period, 'year'=>$year]);
        // }
    }
    public function create(Request $req) {
        $usrid = auth()->user()->id;
        $mth = $req->input('mth');
        $cm = $req->input('cm');
        $half = $req->input('half');
        $paid = Payroll::Paid($mth,$cm,$half)->count();

        if ($paid) {
            return back()->with('status','Payroll cannot be duplicated !');
//            return $paid;
        }

        $payroll = new Payroll;
        $payroll->mth = $mth;
        $payroll->num = Payroll::Month($mth)->count() + 1;
        $payroll->grp_id = $cm;
        $payroll->wageonly = $half;
        $payroll->wagefor = $req->input('wagefor');
        $payroll->wageto = $req->input('wageto');
        $payroll->otfor = ($half ? null : $req->input('otfor'));
        $payroll->otto = ($half ? null : $req->input('otto'));
        $payroll->payon = $req->input('payon');
        $payroll->state = 1;
        $payroll->erp_payby = ($cm==553 ? 'V' : 'T');
        $payroll->CREATED_BY = $usrid;

        $payroll->save();

        $this->addpayemps($payroll);

        return redirect()->route('payrolls');
    }
    function addpayemps($payroll) {
        $empsums = $payroll->EmpSums;
        foreach($empsums as $sum) {
            $emp = App\Models\Emp::find($sum->payemp);
            $exist = PayrollEmp::where('payroll_id', $payroll->id)
                ->where('emp_id', $emp->empid)->get();
            if (empty($exist)) {
                $PayEmp = new PayrollEmp;
                $PayEmp->payroll_id = $payroll->id;
                $PayEmp->emp_id = $emp->empid;
            }
            $PayEmp->rate_id = $emp->id;
            $PayEmp->org_id = $emp->Employee->orgid;
            $PayEmp->pos_id = $emp->Employee->posid;
            $PayEmp->fullwork = (App\Models\AtWork::where('w_empid', $emp->Employee->id)->whereBetween('w_date',[$payroll->wagefore, $payroll->wageto])->get()->sum('attn.lvh + attn.lh1 + attn.lh2') == 0 ? 1 : 0);
            $PayEmp->erp_cc = $emp->Employee->cc;
            $PayEmp->erp_ba = $emp->Employee->BA;

            $items = array('wage', 'cls', 'pos', 'job', 'live', 'edu', 'trans', 'house', 'food', 'prof', 'comm', 'onmove', 'shift', 'bus', 'full');
            foreach($items as $item) {
                $iamt = ItemAmount($item);
                if ( $iamt->amt ) {
                    $payitem = new PayrollItem;
                    $payitem->payemp_id = $PayEmp->id;
                    $payitem->plus = ($amt > 0 ? 1 : -1);
                    $payitem->item_id = $iamt->itemcode;
                    $payitem->amount = $iamt->amt;
                    $payitem->remark = (strlen($iamt->remark) ? $iamt->remark : null);
                    $payitem->gl = 0;
                    $payitem->CREATED_BY = $usrid;
                    $payitem->UPDATED_BY = $usrid;
                    $payitem->save();
                }
            }
            if ($sum->otamt) {
                PayrollItem::insert(['payemp_id'=>$PayEmp->id, 'plus'=>1, 'item_id'=>0]);
            }
        }
    }

    function xaddpayemps($payroll) {
    // fill Employees for payroll;
        $emps = App\Models\EmpPayItems::During($payroll->wagefor, $payroll->wageto)
            ->whereHas('Employee', function($q) use ($payroll) {
                $q->where('cm',$payroll->grp_id);
            })->get()->groupBy('empid');
        foreach($emps as $emp) {
            $exist = PayrollEmp::where('payroll_id', $payroll->id)
                ->where('emp_id', $emp->empid)->get();
            if (empty($exist)) {
                $PayEmp = new PayrollEmp;
                $PayEmp->payroll_id = $payroll->id;
                $PayEmp->emp_id = $emp->empid;
            }
            $PayEmp->rate_id = $emp->id;
            $PayEmp->org_id = $emp->Employee->orgid;
            $PayEmp->pos_id = $emp->Employee->posid;
            $PayEmp->fullwork = (App\Models\AtWork::where('w_empid', $emp->Employee->id)->whereBetween('w_date',[$payroll->wagefore, $payroll->wageto])->get()->sum('attn.lvh + attn.lh1 + attn.lh2') == 0 ? 1 : 0);
            $PayEmp->erp_cc = $emp->Employee->cc;
            $PayEmp->erp_ba = $emp->Employee->BA;
            $Attns = Attns::OfEmp($emp->empid)->During($payroll->wagefor, $payroll->wageto)->sum();

            $incomes = array('wage','cls','pos','job','live','edu','trans','house','food','prof','comm','onmove');
        }
    }
}

