<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class payterm extends Model
{
    protected $table = "commons";

    public function getcredittermAttribute() {
        return $this->ref*1;
    }
    public function getDueDaysAttribute() {
        return ($this->type ? $this->type : $this->ref) * 1;
    }
    public function getisTTAttribute() {
      return ($this->sub == 1);
    }
    public function getisLCAttribute() {
      return ($this->sub == 2);
    }
    public function getisDraftAttribute() {
      return ($this->sub == 3);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('payterm', function (Builder $builder) {
            $builder->where('par', '=', 8485)
                ->orderBy('num');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
}
