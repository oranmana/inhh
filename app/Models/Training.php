<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = "training";
    protected $fillable = [
        'category_id','inside','code','ondate','todate','hours','org_id','coursename','place','rem',
        'amt_fees','amt_expenses','state',
        'CREATED_BY','UPDATED_BY'
    ];

    public function category() {
        return $this->belongsTo('App\Models\Common','category_id');
    }
    public function organizer() {
        return $this->belongsTo('App\Models\Dir','org_id');
    }
    public function trainings() {
        return $this->hasMany('App\Models\Trainings','train_id');
    }
    public function getFeesAttribute() {
        return $this->trainings->sum('fees');
    }
    public function getExpensesAttribute() {
        return $this->trainings->sum('expenses');
    }
    public function getCostAttribute() {
        return $this->Fees + $this->Expenses;
    }
    public function scopeOfYear($q, $yr) {
        return $q->whereYear('ondate',$yr);
    }
    public function scopeOfCategory($q, $cat) {
        if ($cat) {
            return $q->where('category_id',$cat);
        }
    }
}
