<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mkcredit as Credit;

class CreditController extends Controller
{
    public function save(Request $req) {
        $oldcrid = $req->input('cr_id');
        $customerid = $req->input('cr_arid');
        $dirid = $req->input('cr_dirid');
        $typeid = $req->input('cr_type');
        $code = $req->input('cr_code');
        $opendate = $req->input('cr_date');
        $days = $req->input('cr_days');
        $amount = $req->input('cr_amt');

        if ($oldcrid) {
            $oldcr = Credit::find($oldcrid);
            $cr = $oldcr->replicate();
        } else {
            $cr = new Credit;
        }
        $cr->customerid = $customerid;
        $cr->dirid = $dirid;
        $cr->typeid = $typeid;
        $cr->code = $code;
        $cr->opendate = $opendate;
        $cr->creditdays = $days;
        $cr->amount = $amount;

        $cr->save();

        return $cr->id;
    }
}
