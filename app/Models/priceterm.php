<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class priceterm extends Model
{
    protected $table = "commons";

    public function getFreightRqAttribute() {
        return substr($this->ref,1,1) > 0;
    }
    public function getInsureRqAttribute() {
        return substr($this->ref,2,1) > 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('priceterm', function (Builder $builder) {
            $builder->where('par', '=', 1915)
                ->orderBy('num');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
}
