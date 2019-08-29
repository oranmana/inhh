<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveItemController extends Controller
{
    public function update(Request $req)
    {
        $validated = Validator::make($req, array(
            'name'=>'required|min:5',
            'tname'=>'required|min:5',
            'paid'=>'numberic|max:limit'
        ));

        $lvid = $req->input('id');
        $lvcode = $req->input('code');
        $lvname = $req->input('name');
        $lvtname = $req->input('tname');
        $lvnotice = $req->input('notice');
        $lvlimit = $req->input('limit');
        $lvpaid = $req->input('paid');
        $lvholidy = ($req->input('holiday') ? 1 : 0);

        $lv = App\Models\LeaveItem::findOrNew($lvid);
        if ($lv->code != $lvcode)   $lv->code = $lvcode;
        if ($lv->name != $lvname)   $lv->name = $lvname;
        if ($lv->tname != $lvtname) $lv->tname = $lvtname;
        if ($lv->des*1 != $lvnotice)    $lv->des = $lvnotice;
        if ($lv->sub != $lvlimit)   $lv->sub = $lvlimit;
        if ($lv->dir != $lvpaid)    $lv->dir = $lvpaid;
        if ($lv->gl != $lvholiday)  $lv->gl = $lvholiday;

        return redirect()->back()->with('message', 'Successfully updated !!!');
    }
}
