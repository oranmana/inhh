<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpPayItems extends Model
{
    protected $table = 'EmpPayItems';
    protected $fillable = [
        'docid','empid','indate','xdate','on','pay',
        'wage','cls','pos','job','live','edu','trans','food','house','prof','comm','omove',
        'rem','CREATED_BY','UPDATED_BY'
    ];
    public function Employee() {
        return $this->belongsTo('App\Models\Emp','empid');
    }
    public function Document() {
        return $this->belongsTo('App\Models\Doc','docid');
    }
    public function getAmtAllAttribute() {
        return $this->wage + $this->cls + $this->pos + $this->job + $this->live + $this->food
            + $this->edu + $this->trans + $this->house
            + $this->prof + $this->comm + $this->omove;
    }
    public function getDailyAttribute() {
        return $this->wage <= $this->emp->dailymax;
    }
    public function scopeOfEmp($q, $empid) {
        return $q->where('empid', $empid)->orderBy('indate');
    }
    public function scopeDuring($q, $from, $to) {
        // $from, $to is date string Y-m-d
        if ($to > date('Y-m-d') ) {
            $to = date('Y-m-d');
        }
        if ($from > $to) $from = $to;

        return $q->where('indate','<=', $to)
            ->where(function($q) use ($from) {
                $q->where('xdate',null)->orWhere('xdate','>=', $from);
        });
    }
}
