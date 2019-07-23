<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkpoint extends Model
{
    protected $table = 'mkpoint';
    protected $fillable = [
        'dom','code','dirid','portid','cityid','zoneid','pricetermid','paymentid'
    ];

    public function dir() {
        return $this->belongsTo('App\Models\Dir','dirid');
    }
    public function port() {
        return $this->belongsTo('App\Models\Country','portid');
    }
    public function priceterm() {
        return $this->belongsTo('App\Models\priceterm','pricetermid');
    }
    public function payterm() {
        return $this->belongsTo('App\Models\payterm','paymentid');
    }

    public function getCityAttribute() {
        return $this->port->parent();
    }
    public function getZoneAttribute() {
        return $this->city->parent();
    }

    public function getPayCodeAttribute() {
        return $this->payterm->code;
    }

    public function scopeOfDir($q, $dataid) {
        if ($dataid) {
            return $q->where('dirid', $dataid);
        }
    }
    public function scopeOfPrice($q, $dataid) {
        if ($dataid) {
            return $q->where('pricetermid', $dataid);
        }
    }
    public function scopeOfCountry($q, $dataid) {
        if ($dataid) {
            return $q->whereHas('port', function($q) use ($dataid) {
                return $q->where('par', $dataid);
            });
        }
    }
    public function scopeOfPay($q, $dataid) {
        if ($dataid) {
            return $q->where('paymentid', $dataid);
        }
    }
    public function scopeOfASR($q) {
        return $q->where('px_pt', 3000);
    }
}
