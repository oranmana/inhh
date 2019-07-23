<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Payroll;
use App\Models\Calendar as Cal;

use DB;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'mth','num','grp_id','wagefor','wageto','wageonly','otfor','otto','payon','state',
        'erp_payby','erp_vendor','erp_payroll'
    ];
    
    public function paygrp() {
        return $this->belongsTo('App\Models\Common', 'grp_id');
    }
    public function payemps() {
        return $this->hasMany('App\Models\PayrollEmp', 'payroll_id');
    }

    public function payamts() {
        return $this->hasManyThrough(
            'App\Models\PayrollAmt',
            'App\Models\PayrollEmp',
            'payroll_id',
            'payemp_id',
            'id',
            'id'
        );
    }

    // Attribute Set
    public function getEmpListAttribute() {
        $begin = ($this->wagefor < $this->otfor ? $this->wagefor : $this->otfor);
        $end = ($this->wageto > $this->otto ? $this->wageto : $this->otto);
        $emps = Emp::where('indate','<=',$end)
            ->where(function($q) use ($begin) {
                $q->whereNull('qdate')
                ->orwhere('qdate','>=',$begin);
            })->where('cm',$this->grp_id);
        return $emps;
    }
    
    public function getEmpNumAttribute() {
        return $this->payamts()->count();
    }

    public function getEmpSumsAttribute() {
        $sql = "select payemp, min(indate) as since, max(indate) as renew, round(sum(ifnull(otamts,0)),2) as otamt, sum(late) as lates, sum(lvhr) as lvhrs, sum(sc) as shiftc, sum(bs*1) as bus
        from (
        select a.*, b.id, b.indate, b.xdate, b.wage, b.wage/30/8 as perhr, b.wage/30/8*othrs as otamts, ifnull(lvh,0) as lvhr, ifnull(lt,0) as late, ifnull(shc,0) as sc, ifnull(bs,0) as bus
        from (
        select c.id as payemp, c.payroll_id as payroll, b.w_empid as emp, d.otfor, d.otto, b.w_date, b.w_empid, oth10 + oth15*1.5 + oth20 * 2 + oth30 * 3 as othrs,
            lvh, lh1+lh2 as lt, if(wkid=3681,1,0) as shc, if(w_ot1%8,1,0)+if(w_ot2%8,1,0)+if(w_ot3%8,1,0) as bs
        from hrattns as a
            left join hrworks as b on b.w_id = a.id	
            left join payrollemps as c
                left join payrolls as d on c.payroll_id = d.id
            on c.emp_id = b.w_empid and b.w_date between d.otfor and d.otto
        where c.payroll_id = " . $this->id . " and (oth10 + oth15*1.5 + oth20 * 2 + oth30 * 3) > 0
        ) as a
        left join emppayitems as b on a.emp = b.empid and a.w_date between b.indate and ifnull(b.xdate, now())
        ) as a
        group by 1;";
        $empsum = DB::select($sql);
        return $empsum;
    }
//////////////////////////Emp Attributes /////////////////
    public function getEmpRatesAttribute() {
        $begin = ($this->wagefor < $this->otfor ? $this->wagefor : $this->otfor);
        $end = ($this->wageto > $this->otto ? $this->wageto : $this->otto);
        $emps = EmpPayItems::where('indate','<=',$end)
            ->where(function($q) use ($begin) {
                $q->whereNull('xdate')
                ->orwhere('xdate','>=',$begin);
            })->whereHas('Employee', function($q) use ($begin) {
                $q->where('cm',$this->grp_id)
                ->where(function($q) use ($begin) {
                    $q->whereNull('qdate')
                    ->orwhere('qdate','>=',$begin);
                });
            });
        return $emps;
    }
////////////////////////// Emp's Amts Attributes /////////////////

    public function getIncomeAttribute() {
        return $this->payamts()->select('plus','item_id',DB::raw('sum(amount) as amt'))
            ->groupBy(['plus','item_id'])->orderBy('plus','desc');
    }
    // public function Paid($mth,$cm,$wage) {
    //     return App\Models\Payroll::Paid($mth,$cm,$wage)->first()->id;
    // }

    public function scopeYear($q, $year) {
        $q->whereYear('payon',$year)
            ->orderBy('payon','desc')->orderBy('num','desc');
    }
    public function scopeMonth($q, $month) {
        // $month = 'YYYYMM'
        $q->where("mth", $month)
        ->orderBy('payon','desc')->orderBy('num','desc');
    }
    public function scopeGroup($q, $grp) {
        $q->where('grp_id', $cm);
    }
    public function scopePaid($q, $mth, $cm, $wage) {
        $q->where('mth',$mth)->where('grp_id',$cm)->where('wageonly',$wage);
    }
}
