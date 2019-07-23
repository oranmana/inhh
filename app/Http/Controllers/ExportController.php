<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mkinvoice;
use App\Models\mkpoint;

class ExportController extends Controller
{
    public function index($domid=10,$year=0, $month=0) {
        if (! in_array($domid, array(1,2,3))) $domid = 1;
        if ($year==0) $year = date('Y');
        if ($month==0) $month = date('n');
        $saleyrs = mkinvoice::selectRaw('year(bldate) as yr')->groupBy('yr')->having('yr','>',0)->orderBy('yr','desc')->get();
        $salemonths = mkinvoice::whereRaw('year(bldate)='.$year)
            ->selectRaw("month(bldate) as mth, date_format(bldate,'%M') as name")
            ->groupBy('mth')->get();
        $invs = mkinvoice::whereYear('bldate',$year)->whereMonth('bldate',$month)->where('dom',$domid)
        ->orderByRaw("ifnull(invnum,'') > '' desc")->orderBy('invnum')->orderBy('bldate')->get();

        return view('export.list',compact(['domid','year','month','saleyrs','salemonths','invs']));
    }
    public function show($invid) {
        $inv = mkinvoice::find($invid);
        return view('export.card',compact('inv'));
    }

    public function update($recid,$field,$data) {
        $inv = mkinvoice::find($recid);
        return $inv->update(array($field=>$data));
    }

    public function updatepoint($recid,$data) {
        $inv = mkinvoice::find($recid);
        $point = mkpoint::find($data);
        $inv->update(array('pointid'=>$data,'pricetermid'=>$point->pricetermid,'paytermid'=>$point->paymentid));
        return redirect()->back();
    }

    public function save(Request $req) {
        $usrid = auth()->user()->id;

        $etd = $req->input('etd');
        $pointid  = $req->input('pointid');
        $ponum  = $req->input('ponum');
        $sonum  = $req->input('sonum');

        if ($etd > date('Y-m-d') && $customerid > 0 && $pointid > 0)
        $point = mkpoint::find($pointid);
        $inv = new mkinvoice;
        
        $inv->picempid = $usrid;
        $inv->pointid = $pointid;
        $inv->bldate = $etd;
        $inv->ponum = $ponum;
        $inv->sonum = $sonum;

        $inv->dirid = $point->dirid;
        $inv->dom = ($point->dir->nation == 45 ? 3 : 1);
        $inv->paytermid = $point->paymentid;
        $inv->pricetermid = $point->pricetermid;
        
        $inv->CREATED_BY = $usrid;
        $inv->UPDATED_BY = $usrid;

        $inv->save();
        return back();
    }
}
