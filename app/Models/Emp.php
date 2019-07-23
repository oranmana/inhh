<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DB;

class Emp extends Model
{
    protected $table = 'emps';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','dirid','empcode','cm',
        'nm','name','thname','indate','retireage','xdate','qcode','qdate',
        'bplace', 'relg', 'address', 'house', 'tel', 'mobile', 'edu', 'car', 'blood', 'weight', 'height', 'military',
        'cls', 'orgid', 'posid', 'jobid', 'cc',
        'pvdate', 'pvcode', 'pvcom', 'pvemp', 'pvxdate',
        'pwage', 'ppost', 'pfood', 'phouse', 'pcls', 'plive', 'pfuel', 'pedu', 'pdg', 'pmove',
        'taxcode', 'sex', 'nation', 'bdate', 'haddr', 'cardno'
    ];
    protected $casts = [
        'qcode' => 'number', 
    ];
    
    private $dailymax = 3000;   // Maximum Daily Wage per Day
    /// Relation Sets ////
    public function user() {
        return $this->hasOne('App\User','empid');
    }
    public function dir() {
        return $this->belongsTo('App\Models\Dir','dirid');
    }
    public function org() {
        return $this->belongsTo('App\Models\EmpOrganization','orgid');
    }
    public function pos() {
        return $this->belongsTo('App\Models\Common','posid');
    }   
    public function curjob() {
        return $this->belongsTo('App\Models\jobtitle','jobid');
    }   
    public function promotion() {
        return $this->hasMany('App\Models\EmpPromotion','empid')->orderBy('indate','desc');
    }
    public function relatives() {
        return $this->hasMany('App\Models\Dir','empid')->EmpDirectory();
    }
    public function payitems() {
        return $this->hasMany('App\Models\EmpPayItems','empid')->orderBy('indate','desc');
    }
    public function education() {
        return $this->hasMany('App\Models\EmpData','empid')->Education();
    }
    public function trainings() {
        return $this->hasMany('App\Models\Trainings','emp_id')
            ->whereHas('train', function($q) {
                return $q->orderBy('ondate','desc');
            });
    }
    public function attendances() {
        return $this->hasMany('App\Models\AtWork', 'w_empid');
    }
    public function payrolls() {
        return $this->hasMany('App\Models\PayrollEmp','emp_id');
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
    /// Attribute sets /// 
        /// Bio
    public function getUpperEmpsAttribute() {
        return \App\Models\emp::isActive()->whereIn('jobid',$this->curjob->bosses);
    }
    public function getUpperEmpsIDAttribute() {
        return \App\Models\emp::whereIn('jobid',$this->curjob->bosses)->pluck('id');
    }
    public function getLowerEmpsAttribute() {
        $subs = $this->curjob->Subordinates;
        return (sizeof($subs) ? \App\Models\Emp::whereIn('jobid',$subs) : array());
    }
    public function getLowerEmpsIDAttribute() {
        $subs = $this->curjob->Subordinates;
        return ($subs ? \App\Models\Emp::whereIn('jobid',$subs)->pluck('id') : array());
    }
    public function getFullNameAttribute() {
        $sexs = array('Mr.','Ms.','Mrs.');
        return $sexs[$this->sex].' '.$this->name;
    }
    public function getThFullNameAttribute() {
        $sexs = array('นาย','นางสาว','นาง');
        return $sexs[$this->sex]. $this->thname;
    }
    public function getThAbFullNameAttribute() {
        $sexs = array('นาย ','น.ส. ','นาง ');
        return $sexs[$this->sex]. $this->thname;
    }
        // Employment
    public function getIsActiveAttribute() {
        return ($this->qcode == 0);
    }
    // public function getOfOrg($value) {
    //     return ($this->org->code == $value);
    // }
    // public function getOfPos($value) {
    //     return ($this->pos->code==$value);
    // }
    public function getIsHrAttribute() {
        return ($this->org->code == 'HR');
    }
    public function getIsDMAttribute() {
        return ($this->pos->code == 'DM');
    }
    public function getIsTMAttribute() {
        return ($this->pos->code == 'TM');
    }
    public function getWfAttribute() {
        return $this->cm > 3000;
    }
    public function getEmpTmAttribute() {
        $emp = Emp::where('jobid', jobtitle::find($this->jobid)->Tm->id);
        if ($emp) return $emp;
        else return $this->EmpDm;
    }
    public function getEmpDmAttribute() {
        return Emp::where('jobid', jobtitle::find($this->jobid)->Dm->id);
    }
    public function getEmpPresidentAttribute() {
        return Emp::where('jobid', jobtitle::find($this->jobid)->President->id);
    }
    public function getIsDriverAttribute() {
        return ($this->curjob->id == 9084);
    }
    public function getIsDailyAttribute() {
        return ($this->pwage <= $this->dailymax);
    }
    public function getBAAttribute() {
        $CC = Common::CC()->where('erp', $this->ccid);
        return $CC->first()->ref;
    }


    // Attendance Attribute
    public function getAttendanceYearsAttribute() {
        return $this->attendances()
            ->selectRaw('year(w_date) as yr')
            ->groupBy('yr')->orderBy('yr','desc')->get();
    }

    public function AttendanceMonths($year=0) {
        if (!$year) {$year=date('Y');}

        return DB::table('hrworks')->join('hrattns','id','w_id')
            ->where('w_empid',$this->id)->whereYear('w_date',$year)
            ->selectRaw("date_format(w_date,'%Y%m') as mth, date_format(w_date,'%M, %Y') as name,"
                ."sum(w1) as w1h, sum(w2) as w2h, sum(lh1) as lh1h, sum(lh2) as lh2h,"
                ."sum(oth10) as ot10h, sum(oth15) as ot15h, sum(oth20) as ot20h, sum(oth30) as ot30h,"
                ."sum(lvd) as lvday, sum(lvh) as lvhour,"
                ."sum(lvamt) as leaveamount, sum(otamt) as otamount, sum(ltamt) as lateamount")
            ->groupBy('mth')->orderBy('mth')->get();
    }
        // Scope
    public function scopeWorkOn($query, $wdate) {
        $query->where('indate','<=',$wdate)
            ->where(function($query) use ($wdate) {
                $query->where('qdate','>=',$wdate)
                    ->orWhere('qcode',0);
            });
    }
    public function scopeWorkDuring($query, $fromdate, $todate) {
        $query->where('indate','<=',$todate)
            ->Where(function($query) use ($fromdate) {
                $query->where('qdate','>=',$fromdate)
                    ->orWhere('qcode',0);
            });
    }
    public function scopeIsActive($query) {
        $query->where('qcode', 0);
    }
    public function scopeOfCm($query,$cmname) {
        $cm = Common::EmpCms()->where('ref',$cmname)->first();
        $query->where('cm', $cm->id);
    }
    public function scopeOfNotWorker($query) {
        $query->where('cm', '<', 3000);
    }
    public function scopeWhoIn($query, $org='', $pos='') {
        $query->IsActive();
        if ($org > '') {
            $query->whereHas('org', function($query) use ($org, $pos) {
                $query->where('commons.code', $org);
            });
        }
        if ($pos > '') {
            $query->whereHas('pos', function ($query) use ($org, $pos) {
                $query->where('commons.code', $pos);
            });
        }
    }
    static function WhoIs($org='', $pos='') {
        $jobis = jobtitle::JobIs($org,$pos)->first();
        return Emp::isActive()->where('jobid',$jobis->id)->orderBy('indate');
    }
    static function TitleIs($title='') {
        $jobis = jobtitle::TitleIs($title)->first();
        return Emp::isActive()->where('jobid',$jobis->id)->orderBy('indate');
    }

    public function scopeOfOrg($query,$orgid) {
        $query->where('orgid', $orgid);
    }
    
    public function scopeOfPos($query,$posid) {
        $query->where('posid', $posid);
    }
    
    public function scopeOfJob($query,$jobid) {
        $query->where('jobid', $jobid);
    }

    public function scopeHROrg($query) {
        $query->whereHas('org', function($query) {
            $query->where('commons.code', 'HR');
        });
    }

    public function scopeInOrg($query,$orgname) {
        $query->whereHas('org', function($query) use ($orgname) {
            $query->where('commons.code', $orgname);
        });
    }
    
    public function scopeInPos($query,$posname) {
        $query->whereHas('pos', function($query) use ($posname) {
            $query->where('commons.code', $posname);
        });
    }
    
    public function scopeOfJobIs($query, $jobcode) {
        $query->whereHas('curjob', function($query) use ($jobcode) {
            $query->where('code',$jobcode);
        });
    }

    public function scopeOnDuring($query, $ondate) {
        $query->where('indate','<=',$ondate)
            ->where(function($query) use ($ondate) {
                $query->whereNull('qdate')
                    ->orWhere('qdate','>=',$ondate);
            });
    }
    
}
