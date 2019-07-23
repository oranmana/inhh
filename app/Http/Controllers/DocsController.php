<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Builder;
use Auth;
use DB;

use App\Models\Common;
use App\Models\Doc;
use App\Models\DocType;
use App\Models\EmpOrganization;

class DocsController extends Controller 
{
    public function TypeEdit(Request $req) {
        $usrid = auth()->user()->id;
        $typeid = $req->input('typeid');
        $type = DocType::find($typeid);
        if (! $type) {
            $maxnum = \App\Models\DocType::where('par', $typeid)->max('num');
            $type = new DocType;
            $type->main = 5078;
            $type->par = $typeid;
            $type->num = $maxnum + 1;
            $type->CREATED_BY = $usrid;
        }
        
        $type->code = $req->input('code');
        $type->name = $req->input('name');
        $type->ref = $req->input('ref');    // docgroup
        $type->des = $req->input('des');    // team limit
        $type->UPDATED_BY = $usrid;

        $type->save();
        return back();
    }

    public function index($year=0, $orgid=0, $typeid=0, $ppid=0) {
        $user = auth()->user();

        if (! $year) $year = date('Y');
        if (! $orgid && ! $typeid) $orgid = $user->emp->orgid;
        // if (! $typeid) $typeid = 5078;

        $docyrs = Doc::selectRaw('year(indate) as yr')
            ->whereRaw('year(indate) > 0')
            ->groupBy('yr')
            ->get();
        
        $doctypes = Common::where('par',5078)
        ->where(function($q) {
            $q->where('off',0)->orWhere('num',99);
        })->orderByRaw('num=99 desc')->orderBy('num')->get();

        $pptypes = DocType::PP()->where('off',0)->orderBy('num')->get();

        $orglist = EmpOrganization::OrgLists()->get();

        $docs = Doc::whereYear('indate',$year)
            ->where(function($q) use ($orgid) {
                if ($orgid > 0) $q->where('tmid',$orgid);
            })
            ->where(function($q) use ($typeid, $ppid) {
                if ($typeid != 9244) {
                    if ($ppid > 0) {
                        $q->where('typeid',$typeid);
                    } else {
                        $q->whereHas('doctype',function($q) {
                            $q->where('par',3989);
                        });
                    }
                } else {
                    if ($ppid > 0) {
                        $q->where('typeid',$ppid);
                    } else {
                        $q->whereHas('doctype',function($q) {
                            $q->where('par',5078);
                        });
                    }
                }
            })
            ->orderBy('indate', 'desc')
            ->orderBy('typeid')
            ->orderBy('doccode')
            ->paginate(30);
        return view('docs.index', compact('year','orgid','typeid','ppid','docyrs','doctypes','pptypes','orglist','docs'));
        
    }
    public function maindoc(Request $req, $mth=0, $orgid=0, $typeid=5081) 
    {
        $user = auth()->user();
        if ( empty($mth) ) {
            $mth = date('Ym');
        } 
        $yr = date('Y', strtotime($mth.'01'));
        if ( empty($mth) ) {
            $mth = date('Ym');
        } 
        if ( empty($orgid) ) {
            $orgid = auth()->user()->emp->orgid;
        }
        $type = Common::find($typeid);
        $pp = ($type->par == 3989);
        if ( $req->ajax() ) {
            return view('docs.list', ['year'=>$yr,'month'=>$mth,'orgid'=>$orgid,'typeid'=>$typeid,'pp'=>$pp] )->render();
        } else {
            $docyrs = Doc::OfType($typeid)->select(DB::raw('year(indate) yr'))->groupBy('yr')->OrderBy('yr','desc')->get();
            $docmths = Doc::OfType($typeid)->whereYear('indate',$yr)->whereDate('indate','<', date('Y-m-d'))
                ->select(DB::raw("date_format(indate,'%Y%m') mth, date_format(indate,'%M, %Y') name"))->groupBy('mth')->OrderBy('mth','desc')->get();
            if ($user->state >= 8) {
                $orglist = EmpOrganization::OrgLists()->get();
            } else {
                $orgs = $user->emp->org->LowerOrg();
                $orglist = EmpOrganization::On()->whereIn('id', $orgs)->orderBy('ref')->get();
            }
            
            $doctypes = Common::where('par',5078)
            ->where(function($q) {
                $q->where('off',0)->orWhere('num',99);
            })->orderByRaw('num=99 desc')->orderBy('num')->get();
    
            $pptypes = DocType::PP()->where('off',0)->orderBy('num')->get();

            return view('docs.main', ['year'=>$yr,'month'=>$mth,'orgid'=>$orgid,'typeid'=>$typeid,'pp'=>$pp,
                'docyrs'=>$docyrs, 'docmths'=>$docmths, 'orglist'=>$orglist, 'doctypes'=>$doctypes, 'pptypes'=>$pptypes] );
        }
    }

    public function show($docid) {
        $doc = Doc::find($docid);
        return view('docs.show',compact('doc'))->render();
    }

    public function upload(Request $req) {
        $files = $req->file('filedata');
        $docid = $req->input('docid');
        $doc = Doc::find($docid);
        $filenametostore = ($doc->oid ?? 'A.'.$docid) .'.'.$files->getClientOriginalName();
//        $files->move('docs', $docid.'.'.$files->getClientOriginalName());
        // Storage::disk('hanwhathdoc')->put($filenametostore, fopen($files, 'r+'));
        Storage::disk('hanwhathdoc')->put($filenametostore, $files);
        return back();
    }
}
