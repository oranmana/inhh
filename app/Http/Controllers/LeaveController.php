<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Emp;
use App\Models\LeaveRq;


class LeaveController extends Controller 
{
    public function index($yr, $mode, $empid) {
        $user = auth()->user();
        $allowall = ($user->can('isAdmin') || $user->can('isHR'));
        if (! $allowall) {
            $empid = $user->empid;
        } 
        $leaverqs = LeaveRq::OfYear($yr)->UnderEmp($empid);
        // Pending
        if ($mode==1 || empty($mode) ) { 
            $leaverqs->Pending();
        }
        // Approved
        if ($mode==2) {
            $leaverqs->EmpApprovedBy($empid);
        }
        // Rejected
        if ($mode==3) {
            $leaverqs->whereNull('approved_at');
        }
        $leaves = $leaverqs->orderBy('fdate','desc')->get();
        
        return view('leave.list',compact('yr','mode','empid','leaves'))->render();
    }

    public function approve(Request $req) {
        $userid = auth()->user()->id;
        $rqid = $req->input('rqid');
        $mode = $req->input('mode');
        $msg='Error';
        $lv = LeaveRq::find($rqid);
        if ($mode == 1 ) {  
            $lv->verified_by = $userid;
            $lv->verified_at = date('Y-m-d H:i:s');
            $msg = 'Verified';
        } 
        if ($mode == 2) {
            $lv->approved_by = $userid;
            $lv->approved_at = date('Y-m-d H:i:s');
            $msg = 'Approved';
        }
        if ($mode == 3) {
            $lv->state = ($lv->state == 9 ? 0 : 9);
            $msg = ($lv->state == 9 ? 'HR OK' : 'HR Veto');
        }
        $lv->save();
        return $msg;
    }
}
