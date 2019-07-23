<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpClass extends Model
{
    protected $table = "commons";
    protected $primaryKey = "id";
    
    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];

    // public function getTopIdAttribute() {
    //     return 9364;
    // }
    public function getAllowanceAttribute() {
        return($this->des ? $this->des*1 : 0);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('par', function (Builder $builder) {
            $builder->where('par', '=', 9364);
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }
}
