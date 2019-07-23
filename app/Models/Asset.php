<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Models\Emp;

class Asset extends Model
{
    protected $table = "assets";
    protected $fillable = [
          'itemid','des','brand','model','serial','indate','amount','dateout','state',
          'locationid','par','sapitemid','sapcode','oldsap','picid','teamid','CREATED_BY','UPDATED_BY'
    ];

    // Relation
    public function item() {
        return $this->belongsTo('App\Models\Common','itemid');
    }
    public function location() {
        return $this->belongsTo('App\Models\Common','locationid');
    }
    public function org() {
        return $this->belongsTo('App\Models\Common','teamid');
    }
    public function pic() {
        return $this->belongsTo('App\Models\Emp','picid');
    }
    public function sapitem() {
        return $this->belongsTo('App\Models\Common','sapteamid');
    }
    public function logs() {
        return $this->hasMany('App\Models\AssetLog','assetid');
    }


    // Scope
    public function scopeOfOrg($q, $teamid) {
        return $q->where('teamid', $teamid);
    }
    public function scopeOfPic($q, $picid) {
        return $q->where('picid', $picid);
    }
    public function scopeOfItem($q, $itmid) {
        return $q->where('itemid', $itmid);
    }
    public function scopeOfState($q, $state) {
        return $q->where('state', $state);
    }

    // Attributes
    public function getOjStateAttribute() {
        $commonAssetParId = 8520;
        return Common::OfPar($commonAssetParId)->where('code',$this->state)->first();
    }
        // Person of Asset

    public function Manager($state) {
        $managercode = Common::AssetGroup()->whereCode($state)->pluck('tname');
        return Emp::OfJobIs($managercode);
    }
    public function getDisposeManagerAttribute() {
        return $this->Manager(8);
    }
    public function getDisposableManagerAttribute() {
        return $this->Manager(9);
    }
    public function getTeamManagerAttribute() {
        $jobid = $this->pic->jobid;
        $jobtitle = jobtitle::find($jobid);
        $tmjobid = $jobtitle->Tm;
        while ($jobid) {
            $emp = Emp::OfJob($jobid)->isActive()->first();
            if (! empty($emp)) {
                return $emp;
            }
            $jobtitle = jobtitle::find($jobid);
            $jobid = $jobtitle->par;
        }
        return 0;
    }
}
