<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class jobtitle extends Model
{
    const TOPJOB = 9058;
 
    protected $table = "commons";
    protected $primaryKey = "id";

    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];

    public function Position() {
        return $this->belongsTo('App\Models\EmpPosition', 'cat');
    }
    public function Org() {
//        return $this->belongsTo('App\Models\Common', 'ref');
        return $this->belongsTo('App\Models\EmpOrganization', 'ref');
    }
    public function Parent() {
        return $this->belongsTo('App\Models\jobtitle', 'par');
    }
    public function Children() {
        return $this->hasMany('App\Models\jobtitle', 'par');
    }
    public function Boss() {
        return $this->Parent()->with('Boss');
    }
    public function Subordinate() {
        return $this->Children()->with('Subordinate');
    }
    public function emp() {
        return $this->hasMany('App\Models\Emp', 'jobid');
    }
    public function getEmpNumsAttribute() {
        return $this->emp->count();
    }
    public function getBossesAttribute() {
        $bosses = array();
        $boss = $this;
        while (! empty($boss->Parent->id)) {
            $bosses[] = $boss->id;
            $boss = $boss->Parent;
        }
        return $bosses;
    }
    public function getSubordinatesAttribute(&$subs = array()) {
        if ($this->children()->count()) {
            foreach($this->children as $child) {
                $subs[] = $child->id;
                $child->getSubordinatesAttribute($subs);
            }
        }
        return (empty($subs) ? array() : $subs);
    }
    public function getVacantNumsAttribute() {
        return $this->sub - $this->EmpNums;
    }
    public function getToNumAttribute() {
        return $this->sub;
    }
    public function getrqMinAgeAttibute() {
        return $this->gl;
    }
    public function getrqMaxAgeAttibute() {
        return $this->sfx;
    }
    public function getrqMinEduAttibute() {
        return $this->pj;
    }
    public function getrqAvgPayAttibute() {
        return $this->dir;
    }
    public function getPositionClassAttribute() {
        return $this->Position->num;
    }
    // public function getOrgAttribute() {
    //     return Common::find($this->ref);
    // }
    public function getAllowanceAttribute() {
        return $this->type;
    }
    // public function getParentAttribute() {
    //     return Common::find($this->par);
    // }
    public function getParentsAttribute() {
        $pars = [];
        $job = Common::find($this->par);
        $n = 0;
        // while ($job->id != $this->TopId) {
        while ($job->id != self::TOPJOB) {
            $pars[] = $job->id;
            $job = Common::find($job->par);
        }
        return $pars;
    }
    public function getPresidentAttribute() {
        return Common::whereIn('id',$this->parents)
            ->where('cat','=','17')->first();
    }
    public function getDmAttribute() {
        return Common::whereIn('id',$this->parents)
            ->where('cat','=','18')->first();
    }
    public function getTmAttribute() {
        return Common::whereIn('id',$this->parents)
            ->where('cat','=','19')->first();
    }
    public function getSupAttribute() {
        return Common::whereIn('id',$this->parents)
            ->where('cat','=','307')->first();
    }
    public function getFmAttribute() {
        return Common::whereIn('id',$this->parents)
            ->where('cat','=','308')->first();
    }
    public function scopeJobIs($query, $org='', $pos='') {
        if ($org > '') {
            $query->whereHas('org', function($query) use ($org, $pos) {
                $query->where('code', $org);
            });
        }
        if ($pos > '') {
            $query->whereHas('Position', function ($query) use ($org, $pos) {
                $query->where('code', $pos);
            });
        }
    }
    public function scopeTitleIs($query, $title) {
        return $query->where('code',$title);
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('main', function (Builder $builder) {
            $builder->where('main', '=', 9058)->orWhere('id', '=', 9058);
        });
    }
    public function scopeTopJob($query) {
        return $query->where('id', self::TOPJOB);
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
    public function scopeOf($query,$orgid,$posid) {
        return $query->where('ref',$orgid)->where('cat',$posid);
    }
}
