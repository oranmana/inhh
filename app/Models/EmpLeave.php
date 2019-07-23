<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpLeave extends Model
{
    protected $table = 'hrattns';
    protected $fillable = [];
    
    public function attn() {
        return $this->belongsTo('\App\Models\AtWork', 'id');
    }
    public function lv() {
        return $this->belongsTo('\App\Models\Leave', 'lvid');
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('lvid', function (Builder $builder) {
            $builder->where('lvid', '>', 0);
        });
    }

    public function scopeOfEmp($q, $empid, $yr=0) {
        return $q->whereHas('attn', function($q) use ($empid, $yr) {
            if ($yr) {
                $q->whereYear('w_date', $yr);    
            }
            $q->where('w_empid', $empid);
        });
    }

    public function scopeSumLeave($q) {
        // array of lvtype with sum
        // use with SumLeave()->pluck('qty','lvid')
        return $q->select('lvid', \DB::raw('sum(lvd) as qty'))->groupBy('lvid');
    }
    public function scopeYears($q) {
        return $q->whereHas('attn', function($q) {
            return $q->whereRaw('year(w_date) as yr')->groupBy('yr');
        });
    }
    public function scopeLeaveRq($q) {
        return $q->whereHas('lv', function($q) {
            return $q->where('par', 3689);
        });
    }
    public function scopeOutdoor($q) {
        return $q->whereHas('lv', function($q) {
            return $q->where('par', 9196);
        });
    }
    public function scopeOfTypeNm($q, $type) {
        return $q->whereHas('lv', function($q) {
            return $q->where('code',$type);
        });
    }
    public function scopeOfType($q, $type) {
        return $q->where('lvid', $type);
    }
    public function scopeOfPar($q, $parid) {
        return $q->whereHas('lv', function($q) use ($parid) {
            return $q->where('id',$parid);
        });
    }
}
