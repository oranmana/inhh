<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Common;
use App\Models\Country;
use App\Models\Dir;
use App\Models\Emp;
use App\Models\hrApp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Illuminate\Foundation\Validation\ValidatesRequests;
use Auth;

class DirsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        // $dirs = Dir::where('state','>',0)
        //     ->orderBy('name')
        //     ->get();
        $searchtext = (isset($_GET['searchtext']) ? $_GET['searchtext'] : null);
        if ($searchtext==null) {
            $dirs = DB::table('dirs')
                ->where('name','>','')
                ->where('tname','>','')
                ->orderBy('name')
                ->paginate(20);
        } else {
            $dirs = Dir::where('name','LIKE','%'.$searchtext.'%')
                ->orderBy('name')
                ->paginate(20);
        }
        return view('dirs.index',['dirs'=> $dirs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function show(Dir $dir)
    {
        $cms = DB::table('commons')->where('main','=',529)
            ->orderBy('par')->orderBy('name')
            ->get();
        $places = Country::where('sub','=',1)->orderBy('name')->get();
        
        return view('dirs.form',['dir'=> $dir, 'cms' => $cms, 'countries' => $places]);
    }
    public function findtax(Request $req) {
        $taxid = $_GET['taxid'];
        $rqid = $_GET['rqid'];

        $dir = Dir::where('code',$taxid)
            ->select('id','type','sex','name','tname','bdate','tel','email','rem',
                'zdir as edu','pic as inst','appby as gpa',
                'reg as eng','cty as ms','appdate as it', 'tax as eduyr')
            ->orderBy('id')->first();
        $error=array();
        if ($dir) {
            $emp = Emp::where('qcode',0)->where('taxcode',$taxid)->count();
            if ($emp) {
                $error[]=1; // being Employee;
            }
            $rq = hrApp::where('rqid',$rqid)->where('dirid',$dir->id)->count();
            if ($rq) {
                $error[]=2;
            }
            if ($dir->type != 1) {
                $error[]=3;
            }
        }
        $dir->error = $error;
        return json_decode(json_encode($dir), true);
    }
    public function getdir($rqid,$appid) {
        // $dirid = $_GET['dirid'];
        $edlvs = DB::Table('commons')
        ->where('par',3224)->where('off',0)
        ->select('id','code','name','des as tname')
        ->orderBy('code')
        ->get();
        $app = hrApp::find($appid);
        if (! $app) {
            $app = new hrApp;
            $app->rqid = $rqid;
        }
        $app->body = view('hrapps.dirmodal',['app'=>$app,'edlvs'=>$edlvs])->render();
        return $app;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function savedir(Request $req) {
        $usrid = Auth::user()->id;
        $dirid = $req->input('dirid');
        $appid = $req->input('appid');
        $rqid = $req->input('rqid');
        if ($dirid > 0) {
            $dir = Dir::find($dirid);
        } else {
            $dir = new Dir;
            $dir->type=1;
            $dir->code = $req->input('code');
            $dir->CREATED_BY = $usrid;
        }
        
        // $dir->code = $req->input('code');
        if ($req->input('sex') != null)     $dir->sex   = $req->input('sex');
        if ($req->input('name') != null)    $dir->name  = $req->input('name');
        if ($req->input('tname') != null)   $dir->tname = $req->input('tname');
        if ($req->input('bdate') != null)   $dir->bdate = $req->input('bdate');
        if ($req->input('tel') != null)     $dir->tel   = $req->input('tel');
        if ($req->input('email') != null)   $dir->email = $req->input('email');
        // $dir->rem   = $req->input('rem');

        if ($req->input('tax') != null)     $dir->tax  = $req->input('tax');
        if ($req->input('zdir') != null)    $dir->zdir  = $req->input('zdir');
        if ($req->input('pic') != null)     $dir->pic   = $req->input('pic');
        if ($req->input('appby') != null)   $dir->appby = $req->input('appby');

        if ($req->input('reg') != null)     $dir->reg   = $req->input('reg');
        if ($req->input('cty') != null)     $dir->cty   = $req->input('cty');
        if ($req->input('appdate') != null) $dir->appdate = $req->input('appdate');
        if ($req->input('rem') != null)     $dir->rem = $req->input('rem');
        $dir->UPDATED_BY = $usrid;

        $dir->save();

        $apps = hrApp::where('rqid', $req->input('rqid'))
            ->where('dirid', $dir->id);
        if ($apps->count() == 0) {
            $app = new hrApp;
            $app->CREATED_BY = $usrid;
        } else {
            $app = $apps->first();
        }
        $app->rqid = $rqid;
        $app->dirid = $dir->id;
        $app->wdate = $req->input('wdate');
        $app->rem = $req->input('rem');
        $app->UPDATED_BY = $usrid;
        $app->save();
        //dd($dir,$app);
        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function edit(Dir $dir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dir $dir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dir $dir)
    {
        //
    }
}
