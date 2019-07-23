<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Models\mkpoint;

class mkcustomer extends Model
{
    protected $table = "dirs";

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('grp', function (Builder $builder) {
            $builder->where('grp', '=', 536)
                ->orderBy('name');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
    public function scopeFLR($query) {
        return $query->where('eby',9);
    }
    public function scopeASR($query) {
        return $query->where('eby',0);
    }
    public function scopePoint($query) {
        $points = mkpoint::groupby('dirid')->pluck('dirid');
        return $query->whereIn('id',$points);
    }
    public function scopeNotDom($query) {
        return $query->where('nation','!=',45);
    }
}
