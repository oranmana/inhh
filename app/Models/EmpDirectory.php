<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpDirectory extends Model
{
    protected $table = 'dirs';

    public function family() {
        return $this->belongsTo('App\Models\cmRelative','emprelative');
    }    
    public function tax() {
        return $this->belongsTo('App\Models\cmRelative','empcat');
    }    
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('Position', function (Builder $builder) {
            $builder->where('empid','>',0)->where('emprelative','>',0);
        });
    }

}
