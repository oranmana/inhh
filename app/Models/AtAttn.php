<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtAttn extends Model
{
    // Verified Attendance Data
    protected $table = "hrattns";
    protected $primaryKey = "id";
    protected $fillable = [
        'wkid','hl','w1','w2',
        'lm11','lm12','lm21','lm22',
        'lh1','lh2',
        'oh1','oh2','oh3',
        'lvid','lvd','lvh',
        'oth10','oth15','oth20','oth30',
        'lvamt','otamt','ltamt'
    ];
    public function work() {
        return $this->belongsTo('App\Models\AtWork', 'id');
    }
    public function leave() {
        return $this->belongsTo('App\Models\Common', 'lvid');
    }
    
    public function getRateAttribute() {
        $rate = EmpPayItems::OfEmp($his->work->w_empid)
            ->During($this->work->w_date)->first();
    }

    public function getWorkHrsAttribute() {
        return ($this->w1 ? $this->w1 : 0) + ($this->w2 ? $this->w2 : 0);
    }
    public function getLateHrsAttribute() {
        return ($this->lh1 ? $this->lh1 : 0) + ($this->lh2 ? $this->lh2 : 0);
    }
    public function getOtCodeAttribute() {
        //return ($this->ot15 . '/' . $this->ot10 . '-'.$this->ot20 . '/' . $this->ot30);
        return fnum(($this->oth15 > 0 ? $this->oth15 : 
            ($this->oth10 > 0 ? $this->oth10 :
            ($this->oth20 > 0 ? $this->oth20 : 0))),0,0,2)
            . ($this->oth30 > 0 ? '/'. fnum($this->oth30,0,0,2) : '');
    }
    public function getLvCodeAttribute() {
        return ($this->lvd ? $this->leave->code .'/'.fnum($this->lvd,0,0,2) : '' );
    }
    /////////////////// Scope //////////////////////
    public function scopeEmpDuring($q, $empid, $from, $to) {
        $q->whereHas('work', function($q) use ($empid, $from, $to) {
            $q->where('w_empid', $empid)
                ->where('w_date','>=', $from)
                ->where('w_date','<=', $to);
        });
    }
}
