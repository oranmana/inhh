<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hrInterviewer extends Model
{
    protected $table = 'hrinterviews';

    protected $fillable = [
        'appid','empw',
        's1','s2','s3','s4','s5',
        'accept','rem','CREATED_BY', 'UPDATED_BY'
    ];
    public function app() {
        return $this->belongsTo('App\Models\hrApp', 'appid');
    }
    public function emp() {
        return $this->belongsTo('App\Models\Emp', 'empw');
    }
    public function getScoresAttribute() {
        return $this->s1 + $this->s2 + $this->s3 + $this->s4 + $this->s5;
    }
    public function getDecisionTxtAttribute() {
        $txts = array(
            0=>'Yet Decided',
            1=>'Accepted',
            5=>'Interested',
            9=>'Rejected'
        );
        return $txts[$this->accept];
    }
}
