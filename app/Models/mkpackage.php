<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class mkpackage extends Model
{
    protected $table = "commons";

    public function getPackNameAttribute() {
        return $this->code;
    }
    public function getNetKgsAttribute() {
        return $this->name * 1;
    }
    public function getGrossKgsAttribute() {
        return $this->des * 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('priceterm', function (Builder $builder) {
            $builder->where('par', '=', 8504)
                ->orderBy('num');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
}
