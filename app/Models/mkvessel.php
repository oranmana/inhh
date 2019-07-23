<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkvessel extends Model
{
    protected $table = 'mkvessel';
    protected $fillable = [
        'name','CREATE_BY','UPDATED_BY'
    ];
    
}
