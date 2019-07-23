<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkbooking extends Model
{
    protected $table = "mkbooking";
    protected $fillable = [
        'bookdate','code','agentid','agentpersonid','linerid',
        'qlcl','q20','q20h','q40','q40h',
        'freightprice','lclprice','f20price','f40price','thc20price','thc40price','docprice',
        'feederid','feedervoy','etddate',
        'carrierid','carriervoy','etadate',
        'portfromid','porttoid',
        'receivedate','receivedepoid','receivepersonid','receivememo','receivefrom','receivedperson',
        'returndate','returndepoid','returntopersonid','returnmemo','returnto','returnperson',
        'closetime',
        'remark','state','CREATED_BY','UPDATED_BY'
    ];

    public function inv() {
        return $this->hasOne('App\Models\mkinvoice','bookid');
    }
    public function agent() {
        return $this->belongsTo('App\Models\Dir','agentid');
    }
    public function agentperson() {
        return $this->belongsTo('App\Models\Dir','agentpersonid');
    }
    public function liner() {
        return $this->belongsTo('App\Models\Dir','linerid');
    }
    public function feeder() {
        return $this->belongsTo('App\Models\mkvessel','feederid');
    }
    public function carrier() {
        return $this->belongsTo('App\Models\mkvessel','carrierid');
    }
    public function fromport() {
        return $this->belongsTo('App\Models\Country','portfromid');
    }
    public function toport() {
        return $this->belongsTo('App\Models\Country','porttoid');
    }
    public function getFeederNameAttribute() {
        return $this->feeder->name . (strlen($this->feedervoy) ? ' V.'.$this->feedervoy : '');
    }
    public function getCarrierNameAttribute() {
        return $this->carrier->name . (strlen($this->carriervoy) ? ' V.'.$this->carriervoy : '');
    }
    public function getAll20Attribute() {
        return $this->q20 + $this->q20h;
    }
    public function getAll40Attribute() {
        return $this->q40 + $this->q20h;
    }
    public function getFreightAmountAttribute() {
        // Always quoted in USD
        return  $this->qlcl * $this->lclprice
            + $this->All20 * $this->f20price
            + $this->All40 * $this->f40price;
    }
    public function getTHCAmountAttribute() {
        return  $this->All20 * $this->thc20price
            + $this->All40 * $this->thc40price;
    }
    public function scopeYYMM($q, $yymm) {
        $y = substr($yymm,0,4);
        $m = substr($yymm,-2);
        return $q->whereYear('bookdate',$y)->whereMonth('bookdate',$m);
    }
}
