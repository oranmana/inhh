<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $username = 'username';

    protected $fillable = [
        'id', 'name','email', 'username', 'password', 'empid', 
            'sapid', 'eagle', 'expiredate', 'state','timestamps'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getisMasterAttribute() {
        return $this->state == 9;
    }
    public function getisAdminAttribute() {
        return $this->state >= 8;
    }
    public function getisActiveAttribute() {
        return $this->emp->qcode == 0;
    }

    public function dir() {
        return $this->belongsTo('App\Models\Dir', 'dir_id');
    }
    public function emp() {
        return $this->belongsTo('App\Models\Emp', 'empid');
    }
    public function hrrequestby() {
        return $this->hasMany('App\Models\HRrequest');
    }
    public function docby() {
        return $this->hasMany('App\Models\Docs');
    }

}
