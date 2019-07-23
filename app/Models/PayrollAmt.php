<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PayrollAmt extends Model
{
    protected $table = 'payrollamts';
    protected $fillable = [
        'payemp_id','plus','item_id','amount','erp_gl', 'CREATED_BY', 'UPDATED_BY'
    ];

    public function payemp() {
        return $this->belongsTo('App\Models\PayrollEmp', 'payemp_id');
    }
    public function item() {
        return $this->belongsTo('App\Models\Common','item_id');
    }

    public function scopeForItem($query, $expid) {
        return $query->where('item_id',$expid);
    }
    public function scopeForPayId($query, $payid) {
        return $query->whereHas('payemp',function($query) use ($payid) {
            return $query->where('payroll_id',$payid);
        });
    }
}
