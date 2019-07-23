<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmpData extends Model
{
    protected $table = 'empsdata';
    protected $fillable = [
        'grp','empid','code','ref','yr','name','rem','loc','grd','state','CREATED_BY','UPDATED_BY'
    ];
    public function emp() {
        return $this->belongsTo('App\Models\Emp','empid');
    }
    public function EdLevel() {
        return $this->belongsTo('App\Models\Common','code')->EducationLevel();
    }
    public function scopeEducation($q) {
        return $q->where('grp',1)->with(['EdLevel'=>function($q) {
            return $q->orderBy('code','desc');
        }])->orderBy('yr','desc');
    }
}
