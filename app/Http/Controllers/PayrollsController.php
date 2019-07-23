<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payroll;

class PayrollsController extends Controller
{
    public function index($yr=0) {
        if (! $yr) $yr=date('Y');
        $payrolls = Payroll::year($yr)->get();
        return view('payrolls.index',['yr'=>$yr,'payrolls'=>$payrolls]);
    }

    public function add() {
        $mth = date('Ym');
        $wageonly = (date('d') < 15);
        
        if ( ! Payroll::Paid($mth,3186) ) {
            if (! Payroll::Paid($mth,554) ) {
                if (! Payroll::Paid($mth,553)) {
                    if ( date('d') < 15 && ! Payroll::Paid($mth,3186,1) ) {
                        $topay=array($mth,3186,1);
                    } else {
                        $topay=array(0,0,1);
                    }
                } else {
                    $topay=array($mth,553,0);
                }
            } else {
                $topay=array($mth,554,0);
            }
        } else {
            $topay = array($mth,3186,0);
        }
    }
}
