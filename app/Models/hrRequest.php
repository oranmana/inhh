<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

class hrRequest extends Model
{
    protected $table = 'hrrequest';
    protected $fillable = [
        'id', 'indate', 'rqdate', 'jobid','docid',
        'dcid','ppid','des','state','rcid',
        'CREATED_BY','UPDATED_BY'
    ];
    // State - 0:Prepared, 1:Approved, 2:Reviewed, 3:Selected, 9:Cancelled
    // Relationship
    public function job() {
        return $this->belongsTo('App\Models\jobtitle','jobid');
    }
    public function doc() {
        return $this->belongsTo('App\Models\Doc','docid');
    }
    // public function user() {
    //     return $this->belongsTo('App\User','byusrid');
    // }
    public function apps() {
        return $this->hasMany('App\Models\hrApp','rqid')->orderBy('state','desc');
    }
    public function rc() {
        return $this->belongsTo('App\Models\hrContract','rcid');
    }

    // Attributes
    public function getOpenedAttribute() {
        return ($this->rcid == 0);
    }
    public function getStatusAttribute() {
        $states = array(
            1=>array('code'=>1,'name'=>'New','color'=>'light','bg'=>'dark'),
            2=>array('code'=>2,'name'=>'On Applied','color'=>'success','bg'=>'dark'),
            3=>array('code'=>3,'name'=>'Overdued','color'=>'warning','bg'=>'dark'),
            4=>array('code'=>4,'name'=>'Contracted','color'=>'secondary','bg'=>'dark'),
            5=>array('code'=>5,'name'=>'Employed','color'=>'dark','bg'=>'light'),
            9=>array('code'=>9,'name'=>'Cancelled','color'=>'danger','bg'=>'light'),
            10=>array('code'=>10,'name'=>'#N/A#','color'=>'light','bg'=>'dark')
        );
        $state = 10;
        if ($this->state == 9) {
            $state = 9;
        } else {
            if (isset($this->rc) && $this->rc->empid >  0) {
                $state = 5;
            } else {
                if (isset($this->rc) && $this->rc->id > 0) {
                    $state = 4;
                } else {
                    if ($this->rqdate < date('Y-m-d')) {
                        $state = 3;
                    } else {
                        if($this->apps->count() > 0) {
                            $state = 2;
                        } else {
                            $state = 1;
                        }
                    }
                }
            }
        }
        return $states[$state];
    }
}
