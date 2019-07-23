<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLog extends Model
{
    protected $table = "assetlogs";
    protected $fillable = [
        'assetid','actiondate','actiondoc',
        'oldstate','oldlocationid','oldpicid','oldteamid',
        'newstate','newlocationid','newpicid','newteamid',
        'CREATED_BY', 'UPDATED_BY'
    ];

    public function doc() {
        return $this->belongsTo('App\Models\Doc','actiondoc')->withDefault();
    }
    public function oldlocation() {
        return $this->belongsTo('App\Models\Common','oldlocationid');
    }
    public function oldpic() {
        return $this->belongsTo('App\Models\Emp','oldpicid');
    }
    public function oldteam() {
        return $this->belongsTo('App\Models\Common','oldteamid');
    }

    public function newlocation() {
        return $this->belongsTo('App\Models\Common','newlocationid');
    }
    public function newpic() {
        return $this->belongsTo('App\Models\Emp','newpicid');
    }
    public function newteam() {
        return $this->belongsTo('App\Models\Common','newteamid');
    }

    public function getActionNameAttribute() {
        $name = '';
        $from = '';
        $to = '';
        if ($this->oldstate == $this->newstate) {
            if ($this->oldlocationid != $this->newlocationid) {
                $name = "Asset Relocation";
                $from = $this->oldlocation->name;
                $to = $this->newlocation->name;
            }
            if ($this->oldpicid != $this->newpicid) {
                $name = "Asset Assigned";
                $from = $this->oldpic->name;
                $to = $this->newpic->name;
            }
            if ($this->oldteamid != $this->newteamid) {
                $name = "Asset Transferred";
                $from = $this->oldteam->name;
                $to = $this->newteam->name;
            }
        } else {
            switch ($this->newstate) {
                case 0 :
                    $name = "New Asset Entry";
                    $from = '';
                    $to = $this->newpic->name;
                    break;
                case 1 :
                    $name = "Register Requested";
                    $from = $this->oldpic->name;    // Employee
                    $to = $this->newpic->name;      // Team Manager
                    break;
                case 2 :
                    $name = "Register Verified";
                    $from = $this->oldpic->name;    // Team Manager
                    $to = $this->newpic->name;      // ACC Manager
                    break;
                case 3 :
                    $name = "Asset Registred";
                    $from = $this->oldpic->name;    // ACC Manager
                    $to = $this->newpic->name;      // Asset Manager + Team Manager
                    break;
                case 4 :
                    $name = "Reserved at Asset Manager";
                    $from = $this->oldpic->name;    // Team Manager
                    $to = $this->newpic->name;      // Asset Manager
                    break;
                case 5 :
                    $name = "Notice of Lost";
                    $from = $this->oldpic->name;    // Employee 
                    $to = $this->newpic->name;      // Team Manager cc Asset Manager
                    break;
                case 6 :
                    // Out of Date - Unusable
                    $name = "Replacement Request";
                    $from = $this->oldpic->name;    // Employee || Team Manager
                    $to = $this->newpic->name;      // Asset Manager
                    break;
                case 7 :
                    $name = "Repair Request";
                    $from = $this->oldpic->name;    // Employee || Team Manager
                    $to = $this->newpic->name;      // Asset Manager
                    break;
                case 8 :
                    $name = "Disposable Asset";
                    $from = $this->oldpic->name;    // Asset Manager
                    $to = $this->newpic->name;      // Asset Disposable Manager
                    break;
                case 9 :
                    $name = "Asset Disposed";
                    $from = $this->oldpic->name;    // Asset Disposable Manager
                    $to = $this->newpic->name;      // Account Manager
                    break;
                default :
                    $name = "n/a";
                    $from = $this->oldpic->name;    
                    $to = $this->newpic->name;      
                    break;
            }
        }
        return array($name,$from,$to);
    }




}
