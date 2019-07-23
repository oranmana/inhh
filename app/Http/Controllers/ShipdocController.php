<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mkinvoice;
use App\Models\mkpackinglist;

class ShipdocController extends Controller
{
    public function invoice($doctype,$invid) {
        $docs = array(
          111=>'customsinv',
          112=>'customspack'
        );
        $inv = mkinvoice::find($invid);
        return view('export.shipdoc.'.$docs[$doctype],compact('doctype','inv'));
    }
}
