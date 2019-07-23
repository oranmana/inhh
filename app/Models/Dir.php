<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CommonFunction;

class Dir extends Model
{
    const UPDATED_AT = 'eon';
    protected $fillable = [
        'id', 'type', 'sex','par',
        'code','grp','num',
        'nm', 'name','tname','nation',
        'address','tel','fax','email',
        'bdate','xdate', 'cap', 'md', 
        'appby', 'appdate', 'validity',
        'iserp', 'erpdir', 'erpfi', 'erpmm', 'erpsd',
        'empid', 'emprelative','empcat',
        'rem','state','eby','eon',
        'zdir', 'pic', 'reg', 'tax', 'cty'
    ];
    // Relationships
    public function cmgrp() {
        return $this->belongsTo('App\Models\Common','grp');
    }
    public function nationality() {
        return $this->belongsTo('App\Models\mkcountry','nation');
    }
    // public function relativescat() {
    //     return $this->hasOne('App\Models\Common','empcat');
    // }

    // Attributes
    protected $appends = ['isPerson','age'];
    public function getGenderAttribute() {
        $person = array(
            array('Co.,Ltd.','Ltd.Part.','Part.'),
            array('Mr.','Ms.','Mrs')
        );
        return $person[$this->type][$this->sex];
    }
    public function getFullNameAttribute() {
        return $this->getGenderAttribute() . ' ' . $this->name;
    }
    public function getThGenderAttribute() {
        $person = array(
            array('บริษัทจำกัด','ห้างหุ้นส่วนจำกัด','ห้างหุ้นสวนสามัญ','บริษัทมหาชน'),
            array('นาย','น.ส.','นาง')
        );
        return $person[$this->type][$this->sex];
    }
    public function getThFullNameAttribute() {
        return $this->getThGenderAttribute() . ' ' . $this->tname;
    }
    public function getAgeAttribute() {
        return ($this->bdate > '' 
            ? age($this->bdate, ($this->xdate ? $this->xdate : date('Y-m-d'))) 
            : null);
    }
    public function getTaxIdAttribute() {
        preg_match( '/^\+\d(\d{1})(\d{4})(\d{5})(\d{2})(\d{1})$/', $this->tax,  $matches);        
        return join('-',$matches);
    }
    public function getIsThaiAttribute() {
        return in_array($this->nation, array(0,45) ) 
            || in_array( $this->grp, array(534, 536) ) ;
    }
    public function getIsNotThaiAttribute() {
        return in_array( $this->grp, array(534, 536)) 
            || (! in_array($this->nation, array(0,45) ));
    }
    public function getIsInternalAttribute() {
        return $this->cmgrp->cat == 532;
    }
    public function getIsExternalAttribute() {
        return $this->cmgrp->cat != 532;
    }
    public function getIsPersonAttribute() {
        return ($this->type==1);
    }
    public function getEduLevelAttribute() {
        return  Common::find($this->zdir);
    }
    public function getShortNameAttribute() {
        return ($this->nm > '' ? $this->nm : $this->name);
    }
    // Family Relatives
    public function family() {
        return $this->belongsTo('App\Models\cmRelative','emprelative')->Relation();
    }    
    public function taxstate() {
        return $this->belongsTo('App\Models\cmRelative','empcat');
    }    
    public function scopeEmpDirectory($q) {
        return $q->where('empid','>',0)->where('emprelative','>',0);
    }

    public function scopeOfGroup($q, $dataid) {
        return $q->where('grp',$dataid);
    }
    public function scopeInGroup($q, $dataarray) {
        return $q->whereIn('grp',$dataarray);
    }
    public function scopeNameIn($q, $dataid) {
        return $q->where('name','like','%'.$dataid.'%');
    }
    public function scopeInternal($q) {
        return $q->where('grp',552)->whereHas('cmgrp', function($q) {
            return $q->where('cat', 532);
        });
    }
    public function scopeExternal($q) {
        return $q->where('grp','!=',552)->whereHas('cmgrp', function($q) {
            return $q->where('cat', '!=', 532);
        });
    }
}

