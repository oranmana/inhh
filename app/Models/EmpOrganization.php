<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpOrganization extends Model
{
    protected $table = "commons";
    public $timestamps = false;

    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];
    
    const TOPORG = 389;

    public function parent() {
        return $this->belongsTo('App\Models\EmpOrganization','par');
    }
    public function children() {
        return $this->hasMany('App\Models\EmpOrganization','par');
    }
    public function childrens() {
        return $this->children()->with('childrens');
    }
    public function emps() {
        return $this->hasMany('App\Models\Emp','orgid')->where('qcode',0);
    }
    public function getFullNameAttribule() {
        return $this->name . ( strlen($this->des) ? ' '.$this->desc : '');
    }
    public function EmpNum($count=0) {
        if ($this->children) {
            $count += $this->emps->count();
            foreach( $this->children as $child) {
                $count += $child->emps->count();
                return $child->EmpNum($count);
            }
        }
        return $count;
    }    
    
    public function LowerOrg($org = null) {
        if(empty($org)) {$org = array();}
        // $org[$this->id] = $this->name;
        $org[]=$this->id;
        foreach( $this->children as $child ) {
            return $child->LowerOrg($org);
        }
        return $org;
    }    

    public function getFullNameAttribute() {
        return $this->name . ($this->des ? ' ' . $this->des : '');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            // $builder->where('main', '=', 309)->orWhere('id',309)
            $builder->where(function($q) {
                $q->where('main', '=', 309)->orWhere('id',309);
            });
                // ->orderBy('ref');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
    public function scopeOrgLists($query) {
        return $query->where('off',0)->whereRaw('node*1 > 0')->whereRaw('ref between 1 and 3')
            ->orderByRaw('node*1');
    }

}
