<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\Emp;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index($itemid=0, $fnteam=0, $fnpic=0, $fnstate=0) {
        $itempar = Common::find(6453);
        $teams = Common::where('main',309)->where('off',0)->where('ref',3)->orderBy('num')->get();
        $pics = Emp::where('qcode',0)->select('id','nm','name')->orderBy('nm')->get();

        if ($fnstate) {
            $assets = Asset::OfState($fnstate)->orderBy('indate')->get();
        } else {

            if ($itemid + $fnteam + $fnpic) {
                $assets = Asset::where(function($q) use ($itemid, $fnteam, $fnpic) {
                    if ($itemid) $q->OfItem($itemid)->orderBy('itemid');
                    if ($fnteam) $q->OfOrg($fnteam)->orderBy('teamid');
                    if ($fnpic) $q->OfPic($fnpic)->orderBy('picid');
                })->orderBy('state')->orderBy('indate')->get();
            } else {
                $assets = new Asset;
            }

        }
        return view('assets.main',compact('fnteam','fnpic','teams','pics','itempar','assets'));
    }

    public function show($assetid)
    {
        $asset = Asset::find($assetid);
        $assetitems = Common::where('main',6453)
            ->where('off',0)->where('group',0)->select('id','name')->get();

        $data[0] = "Asset #" . $assetid;
        $data[1] = view('assets.show',compact('assetitems','asset'))->render();

        return $data;
    }
}