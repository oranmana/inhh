<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hrApp extends Model
{
    protected $table = 'hrapps';
    protected $fillable = [
        'rqid', 'dirid','wdate','rem', 'amt', 'indate', 'score', 'state',
        'byusrid','docid','rcid'
    ];
    // state = 0:Unready, 1:New, 2:Interviewed, 3:Scored, 4:Unselected, 5:Selected, 6:Contract Prepared, 7:Contracted, 8:Employed, 9:Contract Denied
    public function hrrq() {
        return $this->belongsTo('App\Models\hrRequest','rqid'); 
    }
    public function dir() {
        return $this->belongsTo('App\Models\Dir','dirid');
    }
    public function rcs() {
        return $this->belongsTo('App\Models\hrContract','rcid');
    }
    public function interviewers() {
        return $this->hasMany('App\Models\hrInterviewer','appid');
    }
    public function byuser() {
        return $this->belongsTo('App\User','byusrid');
    }
    public function contract() {
        return $this->HasOne('App\Models\hrContract','appid');
    }

    public function scopeOfRequest($query,$rqidnum) {
        return $query->where('rqid', $rqidnum);
    }

}
