<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtWork extends Model
{
    protected $table = "hrworks";
    protected $primaryKey = "w_id";
    protected $fillable = [
        'w_empid', 'w_empsid','w_date','w_workid',
        'w_ot1','w_ot2','w_ot3',
        'w_lvid','w_rem','w_state',
        'tin','tout','tin2','tout2'
    ];
    // OT Cut every 30 Mins
    public $otcut = 30;

    public function emp() {
        return $this->belongsTo('App\Models\Emp', 'w_empid','id');
    }
    public function wk() {
        return $this->belongsTo('App\Models\Common', 'w_workid');
    }
    public function attn() {
        return $this->HasOne('App\Models\AtAttn','id','w_id');
    }
    public function lvrq() {
        return $this->belongsTo('App\Models\LeaveRq','w_lvid','lv_id');
    }
    public function cal() {
        return $this->belongsTo('App\Models\Calendar','w_date','cldate');
    }
    public function rate() {
        return $this->hasOne('\App\Models\EmpPayItems','w_empid','empid')
            ->where(function($q) {
                return $q->where('xdate', null)->orWhere('xdate','<',$this->w_date);
            })
            ->where('indate','<=',$this->w_date);
    }
    // Attribute
    // Day Property
    public function getIsHolidayAttribute() {
        return (($this->emp->wf ? $this->cal->wf : $this->cal->of) == 3683);
    }
    public function getTimeReadyAttribute() {
        return ($this->tin != null ? $this->tin < $this->tout : $this->tout == null 
            && ($this->emp->wf ? $this->cal->wf : $this->cal->of) == 3683 || $this->w_lvid > 0
        ) && ($this->tin2 != null ? $this->tin2 < $this->tout2 : $this->tout2 == null )
            ;
    }
    public function getWorkOrderAttribute() {
        $wk = $this->wk;
        return ($this->w_ot1 ? $this->w_ot1 : '')
            . $wk->code
            .($this->w_ot2 ? $this->w_ot2 : '')
            .($this->w_ot3 ? '/'.$this->w_ot3 : '');
    }
    // Strtotime
    public function getOutdoorAttribute() {
        return ($this->w_lvid && $this->lvrq ? ($this->lvrq->OutDoor ? 1 : 0) : 0);
    }
    public function getStinAttribute() {
        $stin = strtotime(date('Y-m-d H:i:00', strtotime($this->tin)));
        return ($this->Outdoor ? 
            ($this->tin ? ($stin > $this->StdTimeIn ? $this->WkTimeIn : $stin ) : $this->WkTimeIn)
            : ($this->tin ? $stin : 0) );
    }
    public function getStoutAttribute() {
        $stout = strtotime(date('Y-m-d H:i:00', strtotime($this->tout)));
        return ($this->Outdoor ? 
            ($this->tout ? ($stout < $this->StdTimeOut ? $this->WkTimeOut : $stout ) : $this->WkTimeOut)
            : ($this->tout ? $stout : 0) );
    }
    public function getStin2Attribute() {
        $stin2 = strtotime(date('Y-m-d H:i:00', strtotime($this->tin2)));
        return ($this->tin2 ? $stin2 : 0);
    }
    public function getStout2Attribute() {
        $stout2 = strtotime(date('Y-m-d H:i:00', strtotime($this->tout2)));
        return ($this->tout2 ? $stout2 : 0);
    }
    // Work Time Attendance Properties
    public function getStdTimeInAttribute() {
        $mins=0;
        // ShiftA / N / O
        if (in_array($this->wk->code, array('A','N','O'))) {$mins = (8.5*60);}
        // ShiftB
        if ($this->wk->code == 'B') {$mins = (16.5*60);}
        // ShiftC
        if ($this->wk->code == 'C') {$mins = (24.5*60);}
        return strtotime('+' . $mins . ' min', strtotime( ($this->w_date.' 00:00:00') ));
    }
    public function getStdTimeOutAttribute() {
        $mins=0;
        // Shift N / O
        if (in_array($this->wk->code, array('N','O'))) {$mins = 9*60;}
        // Shift A / B / C
        if (in_array($this->wk->code, array('A','B','C'))) {$mins = 8*60;}
        return strtotime('+' . $mins . ' min', $this->StdTimeIn);
    }
    public function getWkTimeInAttribute() {
        return ($this->w_ot1 ? strtotime('-' . ($this->w_ot1*60) . ' min', $this->StdTimeIn) : $this->StdTimeIn);
    }
    public function getWkTimeOutAttribute() {
        return ($this->w_ot3 ? $this->StdTimeOut 
            : ($this->w_ot2 ? strtotime('+' . ($this->w_ot2*60) . ' min', $this->StdTimeOut) : $this->StdTimeOut) );
    }
    public function getWkTimeIn2Attribute() {
        return ($this->w_ot3 ? strtotime('+' . ($this->w_ot3*60) . ' min', $this->StdTimeOut) : 0);
    }
    public function getWkTimeOut2Attribute() {
        return ($this->w_ot3 ? strtotime('+' . ($this->w_ot2*60) . ' min', $this->WkTimeIn2) : 0);
    }
    // OT Calculation
    public function getOtMinsAttribute() {
        $ots = array(1=>0,2=>0,3=>0,4=>0);
        // OT Before Standard Time-In
        $ots[1] = ($this->w_ot1 > 0 && $this->tin && ($this->StdTimeIn > $this->stin) 
            ? $this->StdTimeIn - 
            ($this->stin > $this->WkTimeIn || $this->emp->isDriver ? $this->stin : $this->WkTimeIn) : 0);
        // OT After Standard Time-Out 
        $ots[2] = ($this->w_ot2 > 0 && $this->tout && ($this->StdTimeOut < $this->stout) 
            ? ($this->stout < $this->WkTimeOut || $this->emp->isDriver ? $this->stout : $this->WkTimeOut) 
            - $this->StdTimeOut : 0);
        // Session Work is OT per Order
        $ots[4] = ($this->w_ot2 && $this->w_ot3 && $this->stin2 && $this->stout2 
            ? ($this->stout2 < $this->WkTimeOut2 ? $this->stout2 : $this->WkTimeOut2) - 
            ($this->WkTimeIn2 < $this->stin2 ? $this->stin2 : $this->WkTimeIn2)  : 0);
        foreach($ots as $k=>$v) {$ots[$k] = $v/60;}
        return $ots;
    }
    public function getOtHrsAttribute() {
        $ots = $this->OtMins;
        foreach($ots as $k=>$ot) {
            $ots[$k] = ($ot - ($ot%$this->otcut)) / 60;
        }
        return $ots;
    }
    public function getAllOtsAttribute() {
        $ots = $this->OtHrs;
        $num = 0;
        foreach($ots as $ot){ $num += $ot; }

        return $num;
    }
    public function getOTH15Attribute() {
        return ($this->IsHoliday ? 0 : $this->AllOts);
    }
    public function getOTH10Attribute() {
        return ($this->IsHoliday && (! $this->emp->isDaily) 
            ? $this->WkHours[1]
        : 0);
    }
    public function getOTH20Attribute() {
        return ($this->IsHoliday && $this->emp->isDaily
            ? $this->WkHours[1]
        : 0);
    }
    public function getOTH30Attribute() {
        return ($this->IsHoliday ? $this->WkHours[2] : 0);
    }
    // Lates Calculation
    public function getLtsAttribute() {
        $lts = array(
            1=>mindiff($this->stin, $this->WkTimeIn),
            2=>mindiff($this->WkTimeOut, $this->stout),
            3=>($this->tin2 ? mindiff($this->stin, $this->WkTimeIn2) : 0) ,
            4=>($this->tout2 ? mindiff($this->WkTimeOut2, $this->stout2) : 0) 
        );
        return $lts;
    }
    public function getLatesAttribute() {
        $lates = array(1=>mindiff($this->stin, $this->StdTimeIn ),
            2=>mindiff($this->StdTimeOut, $this->stout ) );
        return $lates;
    }
    public function getLateScoreAttribute() {
        $lates = $this->Lates;
        $scores = array();
        foreach($lates as $k=>$v) {
            $scores[$k] = ($v > 120 ? 10 : ($v > 5 ? 2 : ($v > 0 ? 1 : 0) ));
        }
        return $scores;
    }
    public function getAllLateScoreAttribute() {
        $scores = $this->LateScore;
        $score = 0;
        foreach($scores as $s) { $score += $s;}
        return $score;
    }
    // Work Hours Calculation
    public function getWkHrs2Attribute() {
        $wk = array(1=>0,2=>0);
        if ($this->emp->isDriver) { // Driver 
            $t1 = $this->stout;
            $t2 = $this->stin;
        } else {
            $t1 = ( $this->WkTimeOut < $this->stout 
                ? $this->WkTimeOut : $this->stout );
            $t2 = ( $this->WkTimeIn > $this->stin 
                ? $this->WkTimeIn : $this->stin );
        }
    //    $wk[1] = mindiff($t1, $t2) - $this->LunchBreakMin;
        $tm = mindiff($t1, $t2);
//        $wk[1] = (($tm - ($tm % $this->otcut)) );
        $wk[1] = $tm - $this->LunchBreakMin;

        if ( $this->stout2 && $this->stin ) {
            $t1 = ($this->WkTimeOut2 < $this->stout2 
                ? $this->WkTimeOut2 : $this->stout2 );
            $t2 = ($this->WkTimeIn2 > $this->stin 
                ? $this->WkTimeIn2 : $this->stin );
            $wk[2] = mindiff($t1, $t2);
        }
        foreach($wk as $k=>$w) {
            $wk[$k] = floor($w - ($w % $this->otcut));
            $wk[$k] = floor($wk[$k] / 60);
            if ($wk[$k]<0) $wk[$k]=0;
        }
        return $wk;
    }
    public function getWkHours2Attribute() {
        $wk = $this->WkHrs;
        $ot = $this->OtHrs;
        $wkh = array(
            1=>$wk[1] - ($ot[1]+$ot[2]),
            2=>$ot[1]+$ot[2]+$wk[2]
        );
        ///////////////// Manual //////////////
        if ($wkh[1] > 8) $wkh[1] = 8;
        return $wkh;
    }
    public function getWkHoursAttribute() {
        $ot = $this->OtHrs;
        $wk = array(1=>0, 2=>0);
        $t1 = ($this->stout > $this->StdTimeOut ? $this->StdTimeOut : $this->stout);
        $t2 = ($this->stin < $this->StdTimeIn ? $this->StdTimeIn : $this->stin);
        $tm = mindiff($t1, $t2) - $this->LunchBreakMin;
        $wk[1] = ($tm - ($tm % $this->otcut)) / 60;
        $wk[2] = $this->AllOts;
        return $wk;
    }
    public function getLunchBreakMinAttribute() {
        $lunchin = strtotime($this->w_date.' 12:30:00');
        $lunchout = strtotime($this->w_date.' 13:30:00');
        $tin = ($this->tin ? $this->stin : 0);
        $tout = ($this->tout ? $this->stout : 0);
        $mins = ($this->tin && $this->tout ? 60 : 0);
        if ($tin > $lunchin && $tin < $lunchout) $mins= $lunchout - $tin;
        if ($tout > $lunchin && $tout < $lunchout) $mins = $tout - $lunchin;
        return ($this->emp->wf ? 0 : $mins);
    }
    // Leave
    public function getVfLvRqAttribute() {
        $rq = new \stdClass();
        $outdoor = ($this->lvrq && (! $this->OutDoor));
        $absent = (($this->tin == null) && ($this->tout == null) ? 1 : 0);
            $rq->id     = ($outdoor ? $this->lvrq->id : 0);
            $rq->lv     = ($outdoor ? $this->lvrq->lv->id : 0);
            $rq->code   = ($outdoor ? $this->lvrq->lv->code : '');
            $rq->name   = ($outdoor ? $this->lvrq->lv->name : '');
            $rq->day    = ($outdoor ? Round((8 - $this->WkHours[1]) / 8,2) : 0);
            $rq->hour   = ($outdoor ? (8 - $this->WkHours[1]) : 0);
        if ($absent) {
            $abs = Common::Find(3682);
            $rq->id     = 0;
            $rq->lv     = $abs->id;
            $rq->code   = $abs->code;
            $rq->name   = $abs->name;
            $rq->day    = 1;
            $rq->hour   = 8;
        }
        return $rq;
    }
    // list of Attendance of the date 
    public function scopeEmpYear($query,$empid,$year=0) {
        if (! $year) { $year = date('Y');}
        // $query->whereHas('emp', function($query) use ($empid, $year) {
        //     $query->where('w_empid', $empid)->whereYear('w_date',$year)->where('w_date','<',date('Y-m-d'));
        $query->where('w_empid',$empid)->whereYear('w_date',$year);
    }
    public function scopeLeaveOfType($query,$typeid) {
        $query->whereHas('attn', function($q) use ($typeid) {
            $q->where('lvid', $typeid);
        });
    }
    public function scopeEmpDate($query,$empid,$date) {
        $query->where('w_empid', $empid)
            ->where('w_date', date('Y-m-d', strtotime($date)));
    }
    public function scopeOfEmp($query,$empid) {
        $query->where('w_empid', $empid);
    }
    public function scopeDuring($query,$from, $to) {
        $query->whereBetween('w_date', [$from, $to]);
    }
}
