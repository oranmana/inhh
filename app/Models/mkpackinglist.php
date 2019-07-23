<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkpackinglist extends Model
{
    protected $table = "mkpackinglist";
    protected $fillable = [
        'invid','sapitemid','sizeid',
        'packquantity','salequantity','loadquantity',
        'unitprice','amount','freightamount','insureamount',
        'netkgs','grosskgs','sqm',
        'refunditem',
        'CREATED_BY','UPDATED_BY'
    ];
    public function inv() { 
        return $this->belongsTo('App\Models\mkinvoice','invid');
    }
    public function item() { 
        return $this->belongsTo('App\Models\sapitem','sapitemid');
    }
    public function size() {
        return $this->belongsTo('App\Models\mkpackage','sizeid');
    }
    public function loaditems() {
        return $this->hasMany('App\Models\mkloaditem','packid');
    }
    public function getProductCodeAttribute() {
        return $this->item->itm_code;
    }
    public function getProductNameAttribute() {
        return $this->item->itm_name;
    }
    public function getProductUOMAttribute() {
        return $this->item->uom->name;
    }
    public function getCustomsProductNameAttribute() {
        return $this->item->customsitem->ref;
    }

    public function getPackNameAttribute() {
        return $this->size->packname;
    }
    public function getLoadedQtyAttribute() {
        return $this->loaditems->sum('quantity');
    }
    public function getSaleKgsAttribute() {
        return $this->PackKgs * $this->packquantity;
    }
    public function getPricePerAttribute() {
        return ($this->uniprice > 500 ? 1000 : 1);
    }
    public function getSaleQtyAttribute() {
        return $this->SalesKgs / $this->PricePer;
    }

    public function scopeOf($query, $invid) {
        return $query->where('invid',$invid);
    }

    
}
