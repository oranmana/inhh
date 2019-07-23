<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class sapitem extends Model
{
    protected $table = 'sap_item';
    protected $primaryKey = 'itm_id';
    protected $fillable = [
        'itm_code','itm_name','itm_ba','itm_type','itm_sub','itm_grp','itm_plant','itm_vc',
        'itm_dcl','itm_coa','itm_pj','itm_p1','itm_p2','itm_p3','itm_pk','itm_uom','i_uom',
        'itm_cname','itm_wdm','itm_kg','FacGroup','itm_prc','itm_c3','itm_c6'
    ];
    public function uom() {
        return $this->belongsTo('App\Models\Common','i_uom');
    }
    public function customsitem() {
        return $this->belongsTo('App\Models\Common','itm_uom');
    }
    
//////////////////////////////////From Code//////////////////////////
    public function getisRMAttribute() {
        return substr($this->itmcode,0,1) == '2';
    }
    public function getisPackAttribute() {
        return substr($this->itm_code,0,2) == '24';
    }
    public function getisTGAttribute() {
        return substr($this->itm_code,0,2) == '36';
    }

    public function getisProductAttribute() {
        return substr($this->itm_code,0,1) == '3';
    }

    public function getisFLRAttribute() {
        return substr($this->itm_code,0,3) == '316';
    }
    public function getisNotFLRAttribute() {
        return substr($this->itm_code,0,3) == '315';
    }

    public function getisASRAttribute() {
        return substr($this->itm_code,0,4) == '3151';
    }

    public function getisEMPAttribute() {
        return substr($this->itm_code,0,4) == '3152';
    }

    public function getASRPackUnitAttribute() {
        $uom = substr($this->itm_code,7,1);
        $packs = array(0=>'', 1=>'FB', 2=>'Bag', 4=>'Drum');
        return (in_array($uom, array(1,2,4)) ? $packs[$uom] : '');
    }

    public function getASRPackNameAttribute() {
        $uom = substr($this->itm_code,8,2);
        $packs = array('01'=>'No Pack', '20'=>'D200', '21'=>'PPW', '60'=>'FB600', '66'=>'FB650', '75'=>'FB750', '80'=>'FB800', '90'=>'FB900');
        return (isset($packs[$uom]) ? $packs[$uom] : '') ;
    }

    public function getASRPackSizeAttribute() {
        $uom = substr($this->itm_code,8,2);
        return $uom * ($uom=='20' || $uom > '21' ? 10 : 1);
    }
    public function getASRPackIDAttribute() {
        $packs = array('00'=>0,'01'=>8517, '20'=>8516, '21'=>8511, '22'=>9000, '60'=>8512, '65'=>8513, '75'=>8514, '80'=>8515, '90'=>9710);
        if (substr($this->itm_code,0,4) == '3162') {
            $packs[01] = 8518;
        }
        $pack = substr($this->itm_code,8,2);
        $id = ($packs[$pack] ? $packs[$pack] : 0);
        return $id;
    }

    public function getCodeTypeAttribute() {
        return ($this->isRM ? 'ROH' 
            : ($this->isTG ? 'HAWA' 
                : ($this->isFLR ? (substr($this->itm_code,3,2)*1 ? 'FERT' : 'HALB')
                    : (in_array(substr($this->itm_code,-1)*1, array(0,7,8,9) ) ? 'FERT' : 'HALB') 
                )
            )
        );      
    }
    
    public function getCodeGradeAttribute() {
        return ($this->FLR ? 0 : substr($this->itm_code,4,3));
    }

    public function getCodeProjectAttribute() {
        return ($this->FLR ? 0 : substr($this->itm_code, 10,1));
    }
/////////////////////////////////////////////////////////////////////
    public function getCustomsNameAttribute() {
        return $this->customsitem->name;
    }
    public function getUOMNameAttribute() {
        // Basically Customs Unit
        return $this->uom->name;
    }

    public function getNameAttribute() {
        return $this->itm_name;
    }
    public function getProductNameAttribute() {
        return $this->itm_cname;
    }
/////////////////////////////////Scope////////////////////////////////
    public function scopeRM($query) {
        return $query->where('itm_code','like','2%')
            ->where('itm_code','not like','24%');
    }
    public function scopePacking($query) {
        return $query->where('itm_code','like','24%');
    }

    public function scopeASR($query) {
        return $query->where('itm_code','like','3151%');
    }
    public function scopeEMP($query) {
        return $query->where('itm_code','like','3152%');
    }
    public function scopeTG($query) {
        return $query->where('itm_code','like','336%');
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('flr', function (Builder $builder) {
            $flr = '3130';
            $builder->where('itm_ba', '!=' , $flr);
        });
    }
}

