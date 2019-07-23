<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Common;

class DocType extends Model
{
    protected $table = "commons";
    protected $fillable = [
        'par','main','num','code','name','tname','cat','sub','type','group','ref','erp','off',
        'CREATED_BY', 'UPDATED_BY'
    ];
    
    public function parent() {
        return $this->belongsTo('App\Models\Common', 'par');
    }

    public function getisPPAttribute() {
        return $this->par == 3989;
    }
    public function getDocCodeAttribute() {
        return ($this->isPP ? 'PP' : ($this->cat ? $this->ref : 'Team') );
    }

    public function NewDoc($typeid=0,$orgid=0) {
        $commontypes = Common::whereIn('par',[3989,5078])->orderBy('par')->orderBy('num');
        $doctype = $commontypes->get()->where('id',$typeid)->first();
        $docgroup = ($doctype->par == 3989 ? $commontypes->get()->where('par',3989) 
            : $commontypes->get()->where('par',3989)->where('type',$doctype->type));
        $doc = Doc::whereYear('indate',date('Y'));
        if ($doctype->par == 3989) {
            $doc->whereIn('typeid',$docgroup);
        } else {
            if ($doctype->type==0) {
                $doc->where('tmid',$orgid)
                    ->where('typeid',$docgroup);
            } else {
                $doc->Doc::where('typeid',$docgroup);
            }
        }
        $dc = $doc->orderByRaw('code*1 desc')->first();
        $num=($dc ? $dc->code*1 : 0);
        return ($num + 1);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('par', function (Builder $builder) {
            $builder->whereIn('par',[3989,5078])
                ->where('off',0)->orderBy('par')->orderBy('num');
        });
    }
    public function scopePP($query) {
        return $query->where('par',3989);
    }
    public function scopeLog($query) {
        return $query->where('par',5078);
    }

}
