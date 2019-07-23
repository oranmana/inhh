<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Education extends Model
{
    protected $table = "commons";
    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('par', function (Builder $builder) {
            $builder->where('par', '=', 3224)
                ->where('off',0)->orderBy('num');
        });
    }
}
