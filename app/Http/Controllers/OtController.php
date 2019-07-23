<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtController extends Controller
{
    public function save(Request $req) {
        $atnid = $req->input('id');
        $work = \App\Models\AtWork::find($atnid);

        $holiday = \App\Models\Calendar::where('cldate', $req->input('caldate'));
        $wf = $work->emp->wf;
        $shift = $req->input('shift');
        $ot1 = $req->input('ot1');
        $ot2 = $req->input('ot2');
        $ot3 = $req->input('ot3');
        if ($ot3 && !$ot2) {
            $ot3 = 0;
        }
        $othr = $ot1 + $ot2;

        $work->w_workid = $shift;
        $work->w_ot1 = $ot1;
        $work->w_ot2 = $ot2;
        $work->w_ot3 = $ot3;
        $work->w_rem = ($othr ? $req->input('rem') : '');
        
        $work->save();

        $otcode = otcode($shift,$ot1,$ot2,$ot3);
        // dd($atnid, $otcode, $work);
        return [$atnid, $otcode];
    }
}
