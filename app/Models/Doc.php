<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CommonFunction;

class Doc extends Model
{
    protected $fillable = [
        'id', 'typeid', 'code','doccode',
        'indate','tmid','name','rem',
        'amt', 'hide','docfrom','docto','ref',
        'state','docby'
    ];

    public function user() {
        return $this->belongsTo('App\User','CREATED_BY');
    }
    public function hrrq() {
        return $this->hasMany('App\Models\hrRequest','docid');
    }
    public function doctype() {
        return $this->belongsTo('App\Models\DocType','typeid');
    }
    public function org() {
        return $this->belongsTo('App\Models\Common','tmid');
    }
    public function getisPPAttribute() {
        return $this->doctype->par == 3989;
    }
    public function getDocNumAttribute() {
        return ($this->isPP ? 'PP' : $this->org->code) . '-'
            . date('y', strtotime($this->indate)) .'-'
            . zero($this->code,4);
    }
    public function getDocLocationAttribute() {
        // return "http://www.hanwhath.com/inH";
        return "http://in.hanwha.co.th/inH";
    }

    public function getFilesUploadedAttribute() {
//        $domain = 'http://www.hanwhath.com/inH';
        $url = $this->doclocation . '/lib/api/doclist.php'
            . '?id=' . $this->oid 
            . '&pp=' . ($this->pp ? 1 : 0);
        $val = file_get_contents($url);
        return json_decode($val);
    }
    
    static function NewCode($doctype, $orgid) {
        $type = \App\Models\Doctype::find($doctype);
        $tm = $type->code;
        $docabb = (empty($type->ref) ? $tm : $type->ref);
        $docname = $type->name;
        $yr = date('Y');
        $docs = \App\Models\Doc::OfYear($yr)->OfTypes($doctype);
        if(! $type->cat) {
            $org = \App\Models\EmpOrganization::find($orgid);
            $docs->OfOrg($org->id);
            $tm = $org->code;
        }
        $max = $docs->max(\DB::raw('code*1')) + 1;
        return array(
            ($max ? $max : 1), 
            $docabb.'-'.($yr % 100).'-'. str_pad($max, 4, '0', STR_PAD_LEFT),
            $type->id,
            $type->name,
        );
    }

    public function scopeOfYear($q, $year) {
        return $q->whereYear('indate', $year);
    }
    public function scopeOfMonth($q, $month) {
        return $q->whereRaw("date_format(indate,'%Y%m')=" . $month );
    }
    public function scopeOfOrg($q, $team) {
        return $q->where('tmid', $team);
    }
    public function scopeOfType($q, $type) {
        return $q->where('typeid', $type);
    }
    public function scopeOfTypes($q, $type) {
        $types = \App\Models\DocType::find($type);
        $cat = $types->cat;
        return $q->whereHas('doctype', function($q) use ($cat) {
            $q->where('cat', $cat);
        });
    }
    public function scopeOfPP($q) {
        return $q->whereHas('doctype', function($q) {
            $q->where('par', 3989);
        });
    }
    public function scopeOfNonPP($q) {
        return $q->whereHas('doctype', function($q) {
             $q->where('par', 5078);
        });
    }
}
