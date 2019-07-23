<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class cmRelative extends Model
{
    protected $table = 'commons';
    protected $primaryKey = "id";

    protected $fillable = [
        'par',
        'main',
        'num',
        'code',
        'name',
        'tname',
        'description',
        'sub',
        'type',
        'group',
        'cat',
        'ref',
        'off',
        'CREATED_BY', 'UPDATED_BY'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('Relationship', function (Builder $builder) {
            $builder->where('main',3217);
        });
    }

    public function scopeRelation($query) {
        return $query->where('par',3217)->where('off',0);
    }

}
