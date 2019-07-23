<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkinvoice extends Model
{
    protected $table = "mkinvoices";
    protected $fillable = [
        'version', 'pj', 'dom', 'invnum','sonum','ponum','blnum',
        'creditid', 'picempid','pointid','bookid',
        'invdate','loaddate','bldate',
        'currencyid','paytermid', 'pricetermid',
        'amt','fobamt','freightamt','insureamt',
        'brokerid', 'brokerdate',
        'qlcl','q20','q20h','q40','q40h',
        'quantity','netweight','grossweight',
        'billnum','memo','state'
    ];

    public function credit() {
        return $this->belongsTo('App\Models\mkcredit','creditid');
    }
    public function point() {
        return $this->belongsTo('App\Models\mkpoint','pointid');
    }
    public function booking() {
        return $this->hasOne('App\Models\mkbooking','id','bookid');
    }
    public function currency() {
        return $this->belongsTo('App\Models\Common','currencyid');
    }
    public function payterm() {
        return $this->belongsTo('App\Models\Common','paytermid');
    }
    public function priceterm() {
        return $this->belongsTo('App\Models\priceterm','pricetermid');
    }
    public function pic() {
        return $this->belongsTo('App\User','picempid');
    }
    public function customs() {
        return $this->hasOne('App\Models\mkcustom','id');
    }
    public function broker() {
        return $this->belongsTo('App\Models\Dir','brokerid');
    }
    public function items() {
        return $this->hasMany('App\Models\mkpackinglist','invid');
    }
    public function boxes() {
        return $this->hasMany('App\Models\mkcontainer','invid');
    }

    public function getInvNumberAttribute() {
        return (strlen($this->invnum) ? substr_replace($this->invnum,'/',4,0) : '');
    }
    public function getInvFullNumberAttribute() {
        return (strlen($this->invnum) ? 'HCT-'.substr_replace($this->invnum,'/',4,0) : '');
    }
    public function getAll20Attribute() {
        return $this->q20 + $this->q20h;
    }
    public function getAll40Attribute() {
        return $this->q40 + $this->q20h;
    }
    public function getStateNameAttribute() {
        $names = array('New','Verified','Confirmed','Booked');
        return $names[$this->state];
    }
    public function getModeAttribute() {
        $modes = array(0=>'',1=>'Road',2=>'Air',3=>'Sea');
        $mode = $this->point->port->sub;
        return $modes[$mode];
    }
    public function getFOBAttribute() {
        return $this->amt - $this->freightamt - $this->insureamt;
    }
    public function getNeedFreightAttribute() {
        $term = $this->priceterm->ref;
        return $term[1] == '1';
    }
    public function getNeedInsureAttribute() {
        $term = $this->priceterm->ref;
        return $term[2] == '1';
    }

    public function scopeOfDom($q, $domid=10) {
        return $q->where('dom',$domid);
    }
    public function scopeOfVersion($q, $version=0) {
        return $q->where('version',$version);
    }
    public function scopeOfYM($q, $yymm) {
        return $q->whereRaw("date_format(bldate,'%Y%m') = " . $yymm);
    }
}
