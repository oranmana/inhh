<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Emp;

class hrContract extends Model
{
    protected $table = 'hrContracts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'grp','code','appid','pp','docid','dirid',
        'empid','jobid','indate','todate','cls','pos','amt',
        'empsign','empwit1','empwit2', 'state'
    ];

    public function Signby() {
        return $this->belongsTo('App\Models\Emp','empsign');
    }
    public function Wit1() {
        return $this->belongsTo('App\Models\Emp','empwit1');
    }
    public function Wit2() {
        return $this->belongsTo('App\Models\Emp','empwit2');
    }
    public function app() {
        return $this->belongsTo('App\Models\hrApp','appid');
    }
    public function dir() {
        return $this->belongsTo('App\Models\Dir','dirid');
    }
    public function emp() {
        return $this->belongsTo('App\Models\Emp','empid');
    }
    // Position
    public function job() {
        return $this->belongsTo('App\Models\jobtitle','jobid');
    }
    public function pos() {
        return $this->belongsTo('App\Models\EmpPosition','posid');
    }
    public function empcls() {
        return $this->belongsTo('App\Models\EmpClass','cls','name');
    }

    // Approval
    // public function pp() {
    //     return $this->belongsTo('App\Models\Doc','ppid','oid');
    // }
    public function doc() {
        return $this->belongsTo('App\Models\Doc','docid');
    }
    public function getProbationAttribute() {
        $prob = hrContract::where('appid',$this->appid)->where('grp',($this->grp?0:1))->select('id')->first();
        return ($prob ? $prob->id : 0);
    }
    public function getProbDateAttribute() {
        return ($this->grp==1 ? null : 
            date('Ymd', strtotime('-1 day', strtotime('+4 month', strtotime($this->indate))))
        );
    }
    public function scopeProbation($query, $grp) {
        return $query->where('grp', $grp);
    }
}
