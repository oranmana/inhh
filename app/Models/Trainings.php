<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainings extends Model
{
    protected $table = "trainings";
    protected $fillable = [
        'train_id','emp_id','trainhours','traindate','fees','expenses','leaverq_id',
        'CREATED_BY','UPDATED_BY'
    ];

    public function Employee() {
        return $this->belongsTo('App\Models\Emp','emp_id');
    }
    public function train() {
        return $this->belongsTo('App\Models\Training','train_id');
    }
    public function leaverq() {
        return $this->belongsTo('App\Models\LeaveRq','leaverq_id');
    }
    public function getAmountAttribute() {
        $fee = $this->fees*1;
        $expenses = $this->expenses*1;
        return $fee + $expenses;
    }
    public function scopeOfTrain($q, $trid) {
        $q->where('train_id', $trid);
    }
}
