<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LeaveRq extends Model
{
    protected $table = "hrlvrqs";
    protected $primaryKey = "id";
    protected $fillable = [
        'empid','lvid','fdate','num','rem','trref','state','lv_id',
        'verified_by', 'verified_at',
        'approved_by', 'approved_at'
    ];
    public function emp() {
        return $this->belongsTo('App\Models\Emp', 'empid');
    }
    public function requestor() {
        return $this->belongsTo('App\user', 'requested_by');
    }
    public function verifier() {
        return $this->belongsTo('App\user', 'verified_by');
    }
    public function approver() {
        return $this->belongsTo('App\user', 'approved_by');
    }
    public function lv() {
        return $this->belongsTo('App\Models\Leave', 'lvid');
    }

    public function getrqNumAttribute() {
        $lvpar = $this->lv->par ?? 3689;
        $lv = $lvpar == 3689 ? 1 : 0;
        $tr = $lvpar == 9192 ? 1 : 0;
        $od = $lvpar == 9196 ? 1 : 0;;
        return ($lv ? 1 : ($tr ? 2 : ($od ? 3 : 0 ) ) );
    }

    public function getApNumAttribute() {
        //return (edate($this->fdate,9) >= edate(0,9) ? 0
        return (strtotime($this->fdate) >= strtotime('today') ? 0
            : $this->approved_at ? 2 : 1 );
    }

    public function getFromDateAttribute() {
        return strtotime($this->fdate);
    }
    public function getTillDateAttribute() {
        return strtotime('+'. ($this->num - 1) . ' day', strtotime($this->fdate) ); 
    }
    public function getStateAttributed() {
        return ($this->approved_by ? 3
            : ($this->verified_by ? 2 : 1));
    }

    public function getVerifyByAttribute() {
    //  DM/18 - 0 - P/17
    //  TM/19 - 0 - DM/18
    //  EG/21 - TM/19 - DM/18
    //  Of/22 - TM/19 - DM/18
    //  Sup/307 - EG/21,22 - TM/19
    //  Fm/308  - EG/21,22 - TM/19
    //  Wf/23  - Sup/307 - EG/21,22
        $post = $this->emp->posid;
        $by = (in_array($post, array(23)) ? 307
            : (in_array($post, array(307,308)) ? [21,22]
            : (in_array($post, array(20,21,22)) ? 19 
            : 0) ) );
        return $this->emp->upperemps->where(function($q) use ($by) {
            if(is_array($by)) {
                $q->whereIn('posid',$by);
            } else {
                $q->where('posid',$by);
            }
        });
    }
    public function getApproveByAttribute() {
        $post = $this->emp->posid;
        $by = (in_array($post, array(23)) ? [21,22]
            : (in_array($post, array(307,308)) ? 19
            : (in_array($post, array(19,20,21,22)) ? 18 : 17) ) );
        return $this->emp->upperemps->where(function($q) use ($by) {
            if(is_array($by)) {
                $q->whereIn('posid',$by);
            } else {
                $q->where('posid',$by);
            }
        });
    }

    public function IsPending($ondate=0) {
        if(! $ondate) $ondate = date('Ymd');
        $on_date = strtotime($ondate);
        return date('Ymd', strtotime($this->fdate)) >= date('Ymd', $on_date);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('empid', function (Builder $builder) {
            $builder->where('empid', '>', 0);
        });
    }

    public function scopePending($q) {
        return $q->whereRaw("date_format(fdate,'%Y%m%d') > date_format(date_sub(now(), interval 1 day),'%Y%m%d')");
    }
    public function scopeOfYear($q,$yr) {
        return $q->whereYear('fdate',$yr);
    }
    public function scopeUnderUser($q,$userid) {
        $boss = \App\User::find($userid);
        $empunders = $boss->emp->LowerEmps->pluck('id');
        return $q->whereIn('empid',$empunders);
    }
    public function scopeUnderEmp($q,$empid) {
        $emp = \App\Models\Emp::find($empid);
        $empunders = ($emp->LowerEmps
        ? $emp->LowerEmps->pluck('id') : array() );
        return $q->whereIn('empid',$empunders);
    }
    public function scopeRequestToEmp($q,$empid) {
        $emp = \App\Models\Emp::find($empid);
        $userid = $emp->user->id;
        return $q->UnderUser($userid)->Pending();
    }
    public function scopeVerifiedBy($q,$userid) {
        return $q->where('verified_by',$userid);
    }
    public function scopeEmpVerifiedBy($q,$empid) {
        $emp = \App\Models\Emp::find($empid);
        $userid = $emp->user->id;
        return $q->UnderUser($userid)->where('verified_by',$userid);
    }
    public function scopeApprovedBy($q,$userid) {
        return $q->where('approved_by',$userid);
    }
    public function scopeEmpApprovedBy($q,$empid) {
        $emp = \App\Models\Emp::find($empid);
        $userid = $emp->user->id;
        return $q->UnderUser($userid)->where('approved_by',$userid);
    }
}
