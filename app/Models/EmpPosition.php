<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpPosition extends Model
{
    protected $table = "commons";
    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];
    public function dfcls() {
        return $this->belongsTo('App\Models\EmpClass','cat');
    }
    public function Allowance($cls='',$status=0) {
        if ($this->sfx > 0) {
            if($cls[0]=='S') {
                if ($status == 0) $alw = $this->sfx;
                if ($status == 1) $alw = $this->gl;
                if ($status > 1) $alw = $this->dir;
            } else {
                $alw = 0;   $alws = array();
                $cs = explode(',', $this->des);
                foreach($cs as $ci) {
                    $ms[] = explode(':', $ci);
                }
                foreach($ms as $m) {
                    if($cls == $m[0]) $alw= $m[1];
                }
            }
        } else {
            $alw = $this->type;
        }
        return $alw;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('Position', function (Builder $builder) {
            $builder->where('par', '=', 15)
                ->orderBy('num');
        });
    }
    public function scopeOn($query) {
        return $query->where('off',0);
    }

}
