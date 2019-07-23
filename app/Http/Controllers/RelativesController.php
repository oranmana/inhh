<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelativesController extends Controller
{
    public function index(Request $res, $empid=0) {
        $emp = DB::Table('emps')->where('id',$empid)->first();
        $relatives = DB::select("select *, tax.name as catname
        from (
            select dirs.id as did, dirs.code as dcode, dirs.sex as sex, elt(dirs.sex+1,'นาย','นส.','นาง') as dsex, 
                dirs.name as dname, dirs.tname as dtname, dirs.tel as dtel, dirs.email as dmail, dirs.bdate as dbdate, dirs.xdate as dxdate,
                cms.id as cmsid, cms.code as rcode, cms.name as rname, cms.des as rtname, dirs.rem as occup, dirs.empcat
            from dirs
                left join commons as cms on cms.par=3217 and emprelative = cms.code
            where dirs.par = " . $emp->dirid .") as rel
            left join commons as tax on tax.par = cmsid and tax.code = empcat;");
        // dd($relatives);
        $html = view('relatives.ajax.index')->with(compact('relatives'))->render();
        if ($res->ajax()) {
            // return view('relatives.ajax.index')
            //     ->with(compact('relatives'))
            //     ->render();
            return $html;
        }
        return view('relatives.index',['relatives'=>$relatives]);
    }
}
