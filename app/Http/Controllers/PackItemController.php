<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Builder;
use App\Models\mkinvoice as Invoice;
use App\Models\mkpackinglist as Pack;
use App\Models\mkpackage as Package;
use App\Models\sapitem as Item;

class PackItemController extends Controller
{
    public function save(Request $req) {
        $usrid = auth()->user()->id;

        $invid = $req->input('invid');
        $packid = $req->input('packid');
        $itemcode = $req->input('itemcode');
        $packqty = $req->input('packqty');
        $unitprice = $req->input('unitprc');

        $item = Item::where('itm_code', $itemcode)->first();
        $sizeid = $item->ASRPackID;
        $size = Package::find($sizeid);
        $amt = round($size->netKgs * $packqty / 1000 * $unitprice,2);

        $pack = Pack::FindOrNew($packid);

        $pack->invid = $invid;
        $pack->sapitemid = $item->itm_id;
        $pack->sizeid = $sizeid;
        $pack->netkgs = $size->NetKgs;
        $pack->grosskgs = $size->GrossKgs;
        $pack->packquantity = $packqty;
        $pack->salequantity = $packqty * $size->NetKgs / 1000;
        $pack->unitprice = $unitprice;
        $pack->amount = $amt;

        if ($packid == 0) $pack->CREATED_BY = $usrid;
        $pack->UPDATED_BY = $usrid;
        
        $pack->save();
//        dd($pack, $item);
        $this->invoiceamount($invid);

        return back();
    }
    public function invoiceamount($invid) {
        $usrid = auth()->user()->id;

        $inv = Invoice::find($invid);
        $packitem = $inv->items;
        $term = $inv->point->priceterm;
        $freightamt = ($term->FreightRq ? $inv->freightamt : 0);
        $insureamt = ($term->InsureRq ? $inv->insureamt : 0);
        // Items Value
        $invamt = 0;
        foreach($packitem as $item) {
            $size = $item->size->netKgs;
            $qty = $size * $item->packquantity / 1000;
            $amt = round($qty * $item->unitprice,2);
            $invamt += $amt;
//            $item->salequantity = $item->SaleKgs/1000;
            $item->amount = $amt;
            $item->save();
        }
        $fob = $invamt - $inv->freightamt - $inv->insureamt;
        $inv->update(array('amt'=>$invamt));
        // Freight 
        if ($term->FreightRq && $freightamt) {
            $tamt = $freightamt;
            foreach($packitem as $item) {
                $amt = round($freightamt / $invamt * $item->amount, 2);
                if ($loop->last) {
                    $amt = $tamt;
                } else {
                    $tamt -= $amt;
                }
                $item->update(array('freightamount'=>$amt, 'UPDATED_BY'=>$usrid));
            }
        }
        
        // Insurance
        if ($term->InsureRq && $insureamt) {
            foreach($packitem as $item) {
                $amt = round($insureamt / $invamt * $item->amount, 2);
                if ($loop->last) {
                    $amt = $tamt;
                } else {
                    $tamt -= $amt;
                }
                $item->update(array('insureamount'=>$amt, 'UPDATED_BY'=>$usrid));
            }
        }
        //dd($packitem, $invamts);
    }
}
