<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mkpoint as Point;
use App\Models\Country;
use App\Models\mkcustomer as Customer;

class PointIdController extends Controller
{
    public function save(Request $req) {
        $customerid = $req->input('customerid');
        $portid = $req->input('portid');
        $priceid = $req->input('priceid');
        $payid = $req->input('payid');

        $points = Point
            ::where('dirid',$customerid)
            ->where('portid',$portid)
            ->where('pricetermid',$priceid)
            ->where('paymentid',$payid);

        if ($points->count() > 0) {
            dd($points->get());
            return $pointnums;
        } else {
            $usrid = auth()->user()->id;
            $port = Country::find($portid);
            $pointcust = Point::OfDir($customerid)->count();
            $customer = Customer::find($customerid);

            $points = Point
                ::where('dirid',$customerid)
                ->where('portid',$portid);
            if ($points->count() ) {
                $maxcode = $points->max('code');
                $nums = explode('-',$maxcode);
                $num = $nums[1]*1;
            } else {
                $num = 0;
            }
            $code = $customer->erpsd . '-' . str_pad($num+1,2,'0',STR_PAD_LEFT);
            
            $point = new Point;

            $point->dirid = $customerid;
            $point->portid = $portid;
            $point->cityid = $port->par;
            $point->zoneid = $port->parent->par;
            $point->dom = ($port->par == 45 ? 10 : 30);
            $point->pricetermid = $priceid;
            $point->paymentid = $payid;
            $point->px_pt = '3000';
            $point->code = $code;
            $point->CREATED_BY = $usrid;
            $point->UPDATED_BY = $usrid;

            $point->save();
            return 0;
        }
    }
}
