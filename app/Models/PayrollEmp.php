<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DB;

class PayrollEmp extends Model
{
    protected $table = 'payrollemps';
    protected $fillable = [ 
        'payroll_id','emp_id','rate_id','org_id','pos_id','fullwork','erp_cc','erp_ba'
    ];

    public function payroll() {
        return $this->belongsTo('App\Models\Payroll', 'payroll_id');
    }
    public function payamts() {
        return $this->hasMany('App\Models\PayrollAmt','payemp_id');
    }
    public function emp() {
        return $this->belongsTo('App\Models\Emp', 'emp_id');
    }
    public function rate() {
        return $this->hasMany('App\Models\EmpPayItems', 'empid', 'emp_id');
    }
    public function otworks() {
        return $this->hasMany('App\Models\AtWork', 'w_empid', 'emp_id')
            ->whereBetween('w_date',[$this->payroll->otfor, $this->payroll->otto]);
    }
    //
    public function getRateAtBeginAttribute() {
        $bg = $this->Payroll->wagefor;
        return EmpPayItems::OfEmp($this->emp_id)
        ->where('indate', '<=', $bg)
        ->where(function($q) use ($bg) {
            return $q->whereNull('xdate')
            ->orwhere('xdate','>=', $bg);
        })
        ->orderBy('indate')->first();
    }

    public function getEmpRatesAttribute() {
        $begindate = $this->payroll->wagefor;
        $emprates = EmpPayItems::where('empid', $this->emp->id)
            ->where('indate','<=', $this->payroll->wageto)
            ->where(function($q) use ($begindate) {
                $q->whereNull('xdate')
                ->orWhere('xdate','>=', $begindate);
            })->orderBy('indate','asc');
        return $emprates->get();
    }

    public function getRateAtEndAttribute() {

        $bg = ($this->emp->qdate != null && $this->emp->qdate < $this->Payroll->wageto ? $this->emp->qdate : $this->Payroll->wageto);

        return EmpPayItems::OfEmp($this->emp_id)
        ->where('indate', '<=', $bg)
        ->where(function($q) use ($bg) {
            return $q->whereNull('xdate')
            ->orwhere('xdate','>=', $bg);
        })
        ->orderBy('indate')->first();
    }
    public function xgetRateChangeAttribute() {
        $begin = $this->RateAtBegin;
        $end = $this->RateAtEnd; 
        return ($begin == $end ? 0 : date_diff(date_create($this->payroll->wageto), date_create($this->payroll->wagefor))->format('%d')+1);
    }

    public function getRateChangeAttribute() {
        $begin = $this->emprates->first();
        $end = $this->emprates->last(); 
        // if quit, emprates' xdate contains quit date
        return ($begin == $end ? 0 : date_diff(date_create($this->payroll->wageto), date_create($end->indate))->format('%d')+1);
    }

    public function getFullWorkAttribute() {
        return ($this->emp->indate <= $this->payroll->wagefor &&
            ($this->emp->qdate == null || $this->emp->qdate >=  $this->payroll->wageto) ? 1 : 0);
    }

    public function getItemShiftAmountAttribute() {
        $rate = 20;
        return $this->AttnSum->shiftc * $rate;
    }
    public function getItemBusAmountAttribute() {
        $amt = 0;
        $rate = 50;
        foreach($this->otworks as $ot) {
            $amt += ($ot->w_ot1%8 ? $rate : 0)
            + ($ot->w_ot2%8 ? $rate : 0) 
            + ($ot->w_ot3%8 ? $rate : 0);
        }
        return $amt;
    }

    public function getItemFullAttnAmountAttribute() {
        $amt = 0;
        $attns = $this->AttnSum;
        $lvs = $attns->lvhrs + $attns->lates;
        if ($lvs == 0) {
            $lastmth = date('Ym',strtotime('last month', strtotime($this->payroll->payon)));
            $empid = $this->emp_id;
            $prev = PayrollAmt::where('item_id',3798)
            ->whereHas('payemp', function($q) use ($empid, $lastmth) {
                $q->where('emp_id', $empid)
                    ->whereHas('payroll', function ($q) use ($lastmth) {
                        $q->where('mth', $lastmth);
                    });
            })->first();
            $prevamt = (empty($prev) ? 0 : $prev->amount);
            $amt = ($prevamt >= 500 ? 700 : ($prevamt >= 250 ? 500 : 250) );
        } 
        return $amt;
    }

    public function getItemFullAttnAmountAttribute() {
        $amt = 0;
    }

    public function ItemAmount($item) {
        if ($item == '') {
            $item = 'wage';
        }
        if ($item == 'shift') {
            return ItemShiftAmount();
        }
        if ($item == 'bus') {
            return ItemBusAmount();
        }
        if ($item == 'full') {
            return ItemFullAttnAmount();
        }
        $begin = $this->emprates->first()[$item];
        $end = $this->emprates->last()[$item]; 
        if ($this->fullwork) {  // อยู่เต็มเดือน
            if ($this->wageonly) {  // งวดกลางเดือน
                if ($item == 'wage') {
                    $amt = ($begin / 2) + (($end - $begin)/30 * $this->ratechange);
                } else {
                    $amt = 0;
                }
            } else {
                $amt = $begin + (($end - $begin)/30 * $this->ratechange);
            }
        } else {
            $amt = ($begin / 30) * $this->RateChange;
        }
        return round($amt,2);
    }

    public function getAttnSumAttribute() {
        $attns = AtAttn::EmpDuring($this->emp_id, $this->payroll->wagefor, $this->payroll->wageto)->select(DB::raw('count(if(wkid=3681,1,null)) as shiftc, sum(if(w1*1>0,1,0)) as wdays, sum(lvh) as lvhrs, sum(oth10+oth15*1.5+oth20*2+oth30*3) as ots, sum(lh1+lh2) as lates'))->first();
        return $attns; 
    }
    //
    public function getItemAttribute($itemid) {
        return $this->payamts()->where('item_id',$itemid);
    }

    // Sum Amount //
    public function getAllIncomeAttribute() {
        return $this->payamts()->where('plus',true)->sum('amount');
    }
    public function getAllDeductAttribute() {
        return $this->payamts()->where('plus',false)->sum('amount');
    }
    // Scope //
    public function scopePayId($q, $payid) {
        return $q->where('payroll_id', $payid)
            ->orderBy('erp_ba')->orderBy('erp_cc')->orderBy('emp_id');
    }

    ///////////////////////////////Attribution////////////////////////////////////////////
    public function getinPeriodAttribute() {
        return $this->emp->indate < $this->payroll->wageto 
            && ( empty($this->emp->qdate) || $this->emp->qdate > $this->payroll->wagefor );
    }
    public function getFirstDateAttribute() {
        $d = ($this->emp->indate > $this->payroll->wagefor ? $this->emp->indate : $this->payroll->wagefor);
        return ($this->inPeriod ? date_create($d) : null);
    }
    public function getLastDateAttribute() {
        $d = (empty($this->emp->qdate) || $this->emp->qdate > $this->payroll->wageto ? $this->payroll->wageto : $this->emp->qdate);
        return ($this->inPeriod ? date_create($d) : null);
    }
    public function getisFullEmployedAttribute() {
        $wagefor = date_create($this->payroll->wagefor);
        $wageto = date_create($this->payroll->wageto);
        return ($this->inPeriod && $wagefor == $this->FirstDate && $wageto == $this->LastDate);
    }
    public function getPayrollDaysAttribute() {
        $interval = date_diff($this->FirstDate, $this->LastDate);
        return intval($interval->format('%a'));
    }
    public function getRatesAttribute() {
        $FirstDate = $this->FirstDate->format('Y-m-d');
        $LastDate = $this->LastDate->format('Y-m-d');
        $rates = \App\Models\EmpPayItems::OfEmp($this->emp_id)->During($FirstDate, $LastDate);
        return $rates->get()->toArray();
    }
    public function RateDiff($name) {
        $r = $this->Rates;
        $newdays = 0;
        if (sizeof($r) > 1) {
            $d = date_create( $r[1]['indate'] );
            $interval = date_diff($d, $this->LastDate);
            $newdays = intval($interval->format('%a'));
        }
        return array('rate'=>(sizeof($r) > 1 ? $r[1][$name] - $r[0][$name] : 0), 'days'=>$newdays );
    }
    ///////////////////////////////Paid Amount///////////////////////////
    public function amtWage() {
        return $this->whereHas('payamts', function($q) {
            $q->where('item_id',3783);
        })->sum('payamts.amount');
    }
    ///////////////////////////////Calculation////////////////////////////////////////////
    public function CalcAmount($name) {
        $rate = $this->Rates[0][$name];
        $RateDiff = $this->RateDiff('wage');
        $diffperday = ($RateDiff['days'] ? $RateDiff['rate'] / 30 : 0);
        if ( $this->isFullEmployed ) {
            $amt = ( $this->payroll->wageonly ? $rate / 2 : $rate ) + ( $diffperday * $RateDiff['days'] );
        } else {
            $amt = ( $rate / 30 * $this->PayrollDays ) + ( $diffperday * $RateDiff['days'] );
        }
        return round($amt,2);
    }
    
    // public function getOTAmountAttribute() {
    //     $attn = \App\Models\AtAttn::wherein('id', $this->otworks->pluck('w_id'))->with('rate');
    //     $amt = $attn->sum(function() {
    //         $rate = $this->rate->wage;
    //         // $otrate = $rate->wage / 30 / 8;
    //         // $othrs = $this->oth10 + ($this->oth15*1.5) + ($this->oth20*2) + ($this->oth30*3);
    //         // return $otrate * $othrs;
    //         return $rate;
    //     });
    //     return round($amt,2);
    // }

}
