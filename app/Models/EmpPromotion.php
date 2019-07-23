<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpPromotion extends Model
{
    protected $table = 'EmpPromotions';
    protected $fillable = [
        'docid','empid','inddate','xdate','on','cls','posid','orgid','jobid','oid','CREATED_BY','UPDATED_BY'
    ];
    public function PromotedDocument() {
        return $this->belongsTo('App\Models\Doc','docid');
    }
    public function Emp() {
        return $this->belongsTo('App\Models\Emp','empid');
    }
    public function EmpOrg() {
        return $this->belongsTo('App\Models\EmpOrganization','orgid');
    }
    public function EmpJob() {
        return $this->belongsTo('App\Models\jobtitle','jobid');
    }
    public function EmpClass() {
        return $this->belongsTo('App\Models\EmpClass','cls','name');
    }
    public function EmpPost() {
        return $this->belongsTo('App\Models\EmpPosition','posid');
    }
}
