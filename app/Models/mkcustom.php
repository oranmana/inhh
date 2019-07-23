<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mkcustom extends Model
{
    protected $table = "mkcustoms";
    protected $fillable = [
        'invid','exporttype','refundcode','exportportid','exportnum',
        'entrydate','date03','date04',
        'exportrate','paymentrate','taxrefundcode',
        'CREATED_BY','UPDATED_BY'
    ];

    public function inv() {
        return $this->belongsTo('App\Models\mkinvoice','invid');
    }
    public function export() {
        return $this->belongsTo('App\Models\Common','exporttype');
    }
    public function refund() {
        return $this->belongsTo('App\Models\Common','refundcode');
    }
    public function exportport() {
        return $this->belongsTo('App\Models\Country','exportportid');
    }
}
