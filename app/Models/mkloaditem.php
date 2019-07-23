<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkloaditem extends Model
{
    protected $table = "mkloaditem";
    protected $fillable = [
        'boxid','packid','quantity','rem',
        'CREATED_BY', 'UPDATED_BY'
    ];
    public function box() {
        return $this->belongsTo('App\Models\mkcontainer','boxid');
    }
    public function order() {
        return $this->belongsTo('App\Models\mkpackinglist','packid');
    }
    public function getProductIdAttribute() {
        return $this->order->item;
    }
}
