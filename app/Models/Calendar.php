<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = "hctcal";
    protected $primaryKey = "id";
    protected $fillable = [
        'cldate', 'holiday', 'rem','of','ofm','ofo','wf','wfm','wfo','state'
    ];

    public function getTimeStrAttribute() {
        return strtotime($this->cldate);
    }
    public function getWeekNumAttribute() {
        return date('W', $this->TimeStr);
    }
    public function getDayAttribute() {
        return date('d', $this->TimeStr);
    }
    public function getDayNumAttribute() {
        return date('w', $this->TimeStr);
    }

    public function scopeYear($query, $yr) {
        return $query->whereYear('cldate', $yr);
    }
    public function scopeMonth($query, $yr, $mth) {
        return $query->whereYear('cldate', $yr)->whereMonth('cldate', $mth);
    }
    public function scopeOfficial($query) {
        return $query->whereState(1);
    }

}
