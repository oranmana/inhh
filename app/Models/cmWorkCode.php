<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cmWorkCode extends Model
{
    protected $table = 'commons';
    protected $primaryKey = 'id';

    public function scopeLeave($q) {
        $q->where('par',3689)->orderby('num');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('workcode', function (Builder $builder) {
            $builder->where('main',3677);
        });
    }


}
