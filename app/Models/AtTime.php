<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtTime extends Model
{
    protected $table = "hrtimes";
    protected $primaryKey = "t_id";
    protected $fillable = [
        't_empcode', 't_empid','t_time','t_raw'
    ];
    public function emp() {
        return $this->belongsTo('App\Models\Emp', 't_empid');
    }
}
