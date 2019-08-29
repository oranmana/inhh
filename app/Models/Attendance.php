<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
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

    public function workcode() {
        return $this->belongsTo('App\Models\Workcode','w_workid');
    }

    public function getWorkDateAttribute() 
    {
        return strtotime($this->w_date);
    }

    public function getIsHolidayAttribute() 
    {
        return $this->w_workid == 3683;
    }
    
    public function getShiftInAttribute() {
        $timestr = $this->workcode->Times[0];
        $nextday = $this->workcode->Times[1];
        $time = strtotime($this->w_date . ' ' . $timestr);
        if ($nextday) {
            $time = strtotime('+' . $nextday .' day', $time);
        }
        return $time;
    }

    public function getShiftOutAttribute() {
        $timestr = $this->workcode->Times[2];
        $nextday = $this->workcode->Times[3];
        $time = strtotime($this->w_date . ' ' . $timestr);
        if ($nextday) {
            $time = strtotime('+' . $nextday .' day', $time);
        }
        return $time;
    }

    public function getOrderInAttribute() 
    {
        if ($this->IsHoliday) return null;
        $time = $this->ShiftIn;
        if ($this->w_ot1) {
            $time = strtotime('-' . ($this->w_ot1 * 60) . ' min', $this->ShiftIn);
        }
        return $time;
    }
    
    public function getOrderOutAttribute() 
    {
        if ($this->IsHoliday) return null;
        $time = $this->ShiftOut;
        if ($this->w_ot2 && !$this->w_ot3) {
            $time = strtotime('+' . ($this->w_ot2 * 60) . ' min', $this->ShiftOut);
        }
        return $time;
    }

    public function getHasBreakAttribute() 
    {
        return ($this->w_ot3 &&  $this->w_ot2);
    }

    public function getpostOrderInAttribute() 
    {
        if (! $this->HasBreak) return null;
        $time = strtotime('+' . ($this->w_ot3 * 60) . ' min', $this->ShiftOut);
        return $time;
    }
    
    public function getpostOrderOutAttribute() 
    {
        if (! $this->HasBreak) return null;
        $time = strtotime('+' . ($this->w_ot2 * 60) . ' min', $this->postOrderIn);
        return $time;
    }

    public function getValidWorkAttribute() 
    {
        return strtotime($this->tin) && strtotime($this->tout);
    }

    public function getWorkInAttribute() 
    // ตัดนาที
    {
        if (! $this->ValidWork) return 0;
        $time = date('Ymd H:i', strtotime($this->tin));
        return strtotime($time);
    }

    public function getWorkOutAttribute() 
    // ตัดนาที
    {
        if (! $this->ValidWork) return 0;
        $time = date('Ymd H:i', strtotime($this->tout));
        return strtotime($time);
    }

    public function getValidBreakAttribute() 
    {
        return strtotime($this->tin2) && strtotime($this->tout2);
    }

    public function getPostWorkInAttribute() 
    // ตัดนาที
    {
        $time = date('Ymd H:i', strtotime($this->tin2));
        return ($this->tin2 ? strtotime($time) : 0);
    }

    public function getPostWorkOutAttribute() 
    // ตัดนาที
    {
        $time = date('Ymd H:i', strtotime($this->tout2));
        return ($this->tout2 ? strtotime($time) : 0);
    }

    public function getpreOTminAttribute() 
    {
        $orderOT = $this->w_ot1;
        if (! $orderOT) return 0;

        $rawOT = abs($this->ShiftIn - $this->WorkIn);
        $minOT = round($rawOT / 60);
        $netOT = $minOT - ($minOT % $this->otcut); 
        return ($netOT > $orderOT ? $orderOT : $netOT);
    }

    public function getpostOTminAttribute() 
    {
        $orderOT = $this->w_ot2 && ! $this->w_ot3;
        if (! $orderOT) return 0;

        $rawOT = abs($this->ShiftOut - $this->WorkOut);
        $minOT = round($rawOT / 60);
        $netOT = $minOT - ($minOT % $this->otcut); 
        return ($netOT > $orderOT ? $orderOT : $netOT);
    }

    public function getbreakOTminAttribute() 
    {
        if (! $this->HasBreak || ! $this->ValidBreak ) return 0;
        $orderOT = $this->w_ot2;
        $rawOT = abs( ($this->postWorkOut > $this->postOrderOut ? $this->postOrderOut : $this->postWorkOut) - ($this->postOrderIn > $this->postWorkIn ? $this->postOrderIn : $this->postWorkIn) );
        $minOT = round($rawOT / 60);
        $netOT = $minOT - ($minOT % $this->otcut); 
        return ($netOT > $orderOT ? $orderOT : $netOT);
    }

    public function getOTminAttribute() 
    {
        return $this->preOTmin + ($this->w_ot3 ? $this->breakOTmin : $this->postOTmin);
    }

    public function getLateInAttribute()
    {
        $time = ($this->WorkIn > $this->ShiftIn ? $this->WorkIn - $this->ShiftIn : 0);
        return round($time / 60);
    }

    public function getEarlyOutAttribute()
    {
        $time = ($this->WorkOut < $this->ShiftOut ? $this->ShiftOut - $this->WorkOut : 0);
        return round($time / 60);
    }

    public function getLateBreakInAttribute()
    {
        if (! $this->HasBreak) return 0;
        $time = ($this->postWorkIn > $this->postOrderIn ? $this->postWorkIn - $this->postOrderIn : 0);
        return round($time / 60);
    }

    public function getEarlyBreakOutAttribute()
    {
        if (! $this->HasBreak) return 0;
        $time = ($this->postOrderOut > $this->postWorkOut ? $this->postOrderOut - $this->postWorkOut : 0);
        return round($time / 60);
    }

    
}
