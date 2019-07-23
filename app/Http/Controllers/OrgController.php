<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\EmpOrganization;
use App\Models\Emp;

class OrgController extends Controller
{
    public function index(Request $req, $orgid=0) 
    {
        $toporg = EmpOrganization::TOPORG;
        $orgpar = EmpOrganization::find($toporg);
        $org = EmpOrganization::find($orgid);
        $emps = ($orgid ? Emp::OfOrg($orgid)->isActive()
            ->with(['curjob','pos'])->orderBy('jobid')->get() : 0);
        if ( $req->ajax() ) {
            return view('org.orglist',compact('org','emps'))->render();
        }
        return view('org.main',compact('toporg','orgpar','orgid','org','emps'));
    }

    public function addunder(\App\Http\Requests\addorgRequest $req) 
    {

        // $valid = Validator::make($req->all(), [
        //     'parid' => 'required',
        //     'code' => 'required|string|max:5',
        //     'name' => 'required|string|min:4|max:30',
        //     'tname' => 'required|string|min:4|max:50',
        //     'ref' => 'required|max:1',
        //     'erp' => 'required|size:7',
        // ]);
        $valid = $req->validated(); 

        if ( $valid->error() )
        {
            // return redirect()->back()->withErrors($valid->errors());
            return ['error', $valid->error() ];
        } 
        else 
        {
            // create Org under $par
            $orgs = array(2=>'Department', 3=>'Team', 4=>'Section',5=>'Unit');
            $org = New EmpOrganization;
            $org->main = 309;
            $org->par = $req->input('parid');
            $org->code = $req->input('code');
            $org->name = $req->input('name');
            $org->tname = $req->input('tname');
            $org->ref = $req->input('ref');
            $org->des = $orgs[$req->input('ref')];
            $org->erp = $req->input('erp');

            $org->save();
            // return back();
            return ['message', $org->name . ' ' . $org->des . ' Added !!'];
        }
    }
    
    public function relocate(Request $rq) 
    {
        $org = EmpOrganization::find(($rq->input('orgid')));
        if ( ! ($rq->input('parid')) ) {
            // remove unit
            // $rq->input(parid) == 0
            $org->off = 1;
        } else {
            $org->par = $rq->input('parid');
        }
        $org->save();
        return 'Done';
    }
    
    public function rename(Request $rq) 
    {
        $orgid = $rq->input('orgid');
        $orgcode = $rq->input('code');
        $orgname = $rq->input('name');
        $orgtname = $rq->input('tname');

        $exist_org = EmpOrganization::find($orgid);
        if ($exist_org->name != $orgname ||
            $exist_org->tname != $orgtname ||
            $exist_org->code != $orgcode ) {
            // create new Org

            // clone new org
            $org = $exist_org->replicate();
            $org->code = $orgcode;
            $org->name = $orgname;
            $org->tname = $orgtname;
            $org->save();

            $orgs = $exist_org->children()->on()->get();
            // move child parent to be under new name
            foreach($orgs as $child) {
                $child->par = $org->id;
                $child->save();
            };
            
            // remove $exist_org
            $exist_org->off = 1;
            $exist_org->save();

            return redirect()->back()->with('status','Rename Done !');
        } else {
            return redirect()->back()->with('error','Nothing to be done !');
        }
    }

}
