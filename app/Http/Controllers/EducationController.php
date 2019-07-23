<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class EducationController extends Controller
{
    public function index(Request $res, $emp=0) {
        $edlvs = DB::Table('commons')
            ->where('par',3224)->where('off',0)
            ->select('id','code','name','des as tname')
            ->orderBy('code')
            ->get();
        $edus = DB::Table('empsdata')
            ->join('commons', function($join) {
                $join->on('empsdata.code','=','commons.code')
                    ->where('commons.par','=',3224);
            })
            ->select('empsdata.id as edid',
                'empsdata.emp as empid',
                'empsdata.yr as edyr',
                'commons.id as lvid',
                'commons.name as lvname',
                'empsdata.name as edname',
                'empsdata.rem as edmajor',
                'empsdata.grd as edgpa'
                )
            ->where('grp',1)->where('emp',$emp);
        if ($res->ajax()) {
            return '';
        }
        return ['edlvs'=>$edlvs, 'edus'=>$edus];
    }
}
