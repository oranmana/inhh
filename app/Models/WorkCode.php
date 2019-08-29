<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkCode extends Model
{
    protected $table = "Commons";

    public function getTimesAttribute()
    {    
        return explode(",", $this->tname);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('id', function (Builder $builder) {
            $builder->whereIn('id', array(3678,3679,3680,3681,3696,3683) );
        });
    }    
}
