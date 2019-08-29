<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    public $timestamps = false;
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $fillable = [
        'par',
        'main',
        'num',
        'code',
        'name',
        'tname',
        'description',
        'sub',
        'type',
        'group',
        'cat',
        'ref',
        'off',
        'CREATED_BY', 'UPDATED_BY'
    ];
    
    public function chparent() 
    {
        return $this->belongsTo('App\Models\Common','par');
    }
    public function chtype() 
    {
        return $this->belongsTo('App\Models\Common','type');
    }
    public function children()
    {
        return $this->hasMany('App\Models\Common','par','id');
    }
    public function childrens() 
    {
        return $this->children()->with('childrens');
    }

/////////////////////SAP////////////////////////
    public function getIsCCAttribute() {
        return $this->main == 6879 && $this->group == 0;
    }
    public function getBAAttribute() {
        return ($this->isCC ? $this->ref : 0);
    }
    public function scopeOrg($query) {
        return $query->where('main',309)
            ->where('off',0)
            ->whereIn('ref','<',6)
            ->whereNotNull('erp')
            ->orderBy('ref')->orderBy('num');
    }
    public function scopeLeaveRq($query) {
        return $query->whereIn('par',[3689,9192,9196])
            ->where('off',0)
            ->orderBy('par')
            ->orderBy('num');
    }    
    public function scopeWfShift($query) {
        return $query->where('par',3690)
            ->where('off',0)
            ->orderBy('num');
    }    
    public function scopeEmpGroup($query) {
        return $query->where('cat',532)
            ->where('off',0)
            ->where('group',0)
            ->orderBy('code');
    }    
    public function scopeEducationLevel($query) {
        return $query->where('par',3224)
            ->where('off',0)
            ->where('group',0)
            ->orderBy('code');
    }    
    public function scopeFamily($query) {
        return $query->where('par',3217)
            ->where('main',3217)
            ->where('num','<',6)
            ->where('off',0)
            ->where('group',0)
            ->orderBy('num');
    }    
    public function scopeRelatives($query) {
        return $query->where('par',3217)
            ->where('main',3217)
            ->where('off',0)
            ->where('group',0)
            ->orderBy('num');
    }    
    public function scopeTraining($query) {
        return $query->where('main',9199)
            ->where('off',0)
            ->where('group',0)
            ->orderBy('par')->orderBy('num');
    }    
    public function scopeReligions($query) {
        return $query->where('main',3212)
            ->where('off',0)
//            ->where('group',0)
            ->orderBy('num');
    }    
    public function scopeEmpPositions($query) {
        return $query->where('par',15)
            ->where('off',0)
            ->orderBy('num');
    }    
    public function scopePayrollItems($query) {
        return $query->where('main',3782)
            ->where('cat','>',0)
            ->where('off',0)
            ->whereNotNull('code')
            ->whereNotNull('erp');
    }    
    public function scopeOfPar($query,$parentid) {
        return $query->where('par',$parentid)
            ->orderBy('num');
    }    
    public function scopeAssetGroup($query) {
        return $query->where('par',8520)
            ->orderBy('num');
    }    
    public function scopeEmpCms($query) {
        return $query->where('cat',532)
            ->where('group',0)
            ->orderBy('id');
    }    

    public function scopeCreditTypeforExport($query) {
        return $query->where('par',4419)
            ->where('sub','>',0)
            ->orderBy('num');
    }    
    public function scopeCreditTypeforDom($query) {
        return $query->where('par',4419)
            ->where('sub',0)
            ->orderBy('num');
    }    
    public function scopeDirType($query) {
        return $query->where('main', 529);
    }
    public function scopeDirInternal($query) {
        return $query->where('group',0)
            ->where('main', 529)
            ->where(function($query) {
                $query->where('cat',532)
                    ->orWhere('sub',0);
            });
    }
    public function scopeDirExternal($query) {
        return $query->where('main',529)
            ->where('cat','!=',532)
            ->where('sub','!=',0)
            ->where('group',0)
            ->orderBy('par')
            ->orderBy('num');
    }
/////////////////////SAP////////////////////////
    public function scopeCC($query) {
        return $query->where('main',6879)
            ->where('group',0);
    }

}
