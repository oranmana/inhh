<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PayrollItem extends Model
{
    protected $table = "commons";

    public function parent() {
        return $this->belongsTo('App\Models\Common','par');
    }
    public function child() {
        return $this->HasMany('App\Models\Common','par');
    }
    public function getforKRAttribute() {
        return substr($this->ref,0,1) > 0;
    }
    public function getforOFAttribute() {
        return substr($this->ref,1,1) > 0;
    }
    public function getforWFAttribute() {
        return substr($this->ref,2,1) > 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('main', function (Builder $builder) {
            $mainitem = 3782;
            $builder->where('main', $mainitem);
        });
    }
    public function scopeOfPar($q, $parid) {
        return $q->where('par', $parid)->orderBy('num');
    }
    public function scopeOn($q) {
        return $q->where('off', 0);
    }

}
