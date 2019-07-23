<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkcontainer extends Model
{
    protected $table = "mkloadbox";
    protected $fillable = [
        'invid','sizeid','connum','sealnum',
        'datein','dateload','dateout','remark',
        'CREATED_BY', 'UPDATED_BY'
    ];
    public function inv() {
        return $this->belongsTo('App\Models\mkinvoice','invid');
    }
    public function size() {
        return $this->belongsTo('App\Models\Common','sizeid');
    }
    public function items() {
        return $this->hasMany('App\Models\mkloaditem','boxid');
    }

}
