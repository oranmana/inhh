<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
{
    protected $table  = 'countries';
    protected $fillable = [
        'par', 'num','sub',
        'code','name','tname','ref','des',
        'group','off',
        'CREATED_BY','UPDATED_BY'
    ];
    public function parent() {
        return $this->belongsTo('App\Models\Country','par');
    }
    public function getIsZoneAttribute() {
        return $this->sub == 0;
    }
    public function getIsCountryAttribute() {
        return $this->sub == 1;
    }
    public function getIsPortAttribute() {
        return in_array($this->sub, array(2,3));
    }
    public function getIsAirportAttribute() {
        return $this->sub == 2;
    }
    public function getIsSeaPortAttribute() {
        return $this->sub == 3;
    }
    public function getIsProvinceAttribute() {
        return $this->sub == 11;
    }
    public function getIsDistrictAttribute() {
        return $this->sub == 12;
    }
    public function getIsSubDistrictAttribute() {
        return $this->sub == 3;
    }
    public function getZipAttribute() {
        return ($this->sub > 11 ? code : '');
    }
    public function getCountryNameAttribute() {
        // for Port
        return ($this->isort ? $this->parent->name : $this->sub);
    }

    public function getPortNameAttribute() {
        return (in_array($this->sub, array(2,3)) ? 
            $this->name.', '.$this->parent->name : '');
    }
    public function getFullNameAttribute() {
        switch ($this->sub) {
            case 2 || 3 :
                $name = $this->name .', ' . strtoupper($this->parent->name); 
            case 11 : 
                $name = 'จ.' . $this->name; 
            case 12 :
                $name = 'อ.' . $this->name . ' ' . $this->parent->FullName; 
            case 13 :
                $name = 'ต.' . $this->name . ' ' . $this->parent->FullName; 
            default :
                $name = $this->name;
        }
        return $name;
    }
    public function scopeZones($q) {
        return $q->where('par',0)
            //->orWhere('id',45)
            ->orderByRaw('id=45 asc')->orderBy('des');
    }
    public function scopeCountries($q) {
        return $q->where('sub',1);
    }
    public function scopePort($q) {
        return $q->whereIn('sub',array(2,3) );
    }
    public function scopeOfCountry($q,$parid) {
        return $q->where('par', $parid);
    }
    public function scopeLoadingPort($q) {
        return $q->where('par',45)->whereIn('sub',array(1,2,3));
    }
}
