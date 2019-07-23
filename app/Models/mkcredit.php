<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkcredit extends Model
{
    protected $table = "mkcredit";
    protected $fillable = [
        'dirid','typeid',
        'code','opendate',
        'beneficiary','applicant','consignee','notify','description',
        'shippingmark', 
        'blnum', 'bank', 'drawing','drawee',
        'creditdays','fromport','toport','placeofdelivery',
        'certbeneficiary','certcoa'
    ];

    public function dir() {
        return $this->belongsTo('App\Models\Dir','dirid');
    }
    public function credittype() {
        return $this->belongsTo('App\Models\Common','typeid');
    }
    public function scopeOfCustomer($q, $dataid) {
        return $q->where('customerid', $dataid);
    }
    public function scopeOfDir($q, $dataid) {
        return $q->where('dirid', $dataid);
    }
    public function scopeValid($q) {
        // $validdate = date('Y-m-d', strtotime('-1 year'));
        // return $q->where('opendate','>', $validdate);
        return $q->where('state',0);
    }
}
