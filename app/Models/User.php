<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'id', 'name','email', 'username', 'log_pw', 'dir_id', 'sapid', 'eagle', 'expiredate', 'state','timestamps'
    ];
    protected $hidden = [
        'password', 'remember_token'
    ];
}
