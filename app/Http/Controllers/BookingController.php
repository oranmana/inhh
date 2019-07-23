<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mkinvoice as Invoice;
use App\Models\mkbooking as Booking;

class BookingController extends Controller
{
    public function addfrominv(Request $req) {
        $usrid = auth()->user()->id;

        $invid = $req->input('invid');
        $booknum = $req->input('booknum');
        
        // inv->Loading Port default

        $inv = Invoice::find($invid);
        $book = new Booking;
        $book->code = $booknum;
        
        // set booking default from inv
        $book->bookdate = now();
        $book->etddate = $inv->bldate;

        $book->state = 0;
        $book->CREATED_BY = $usrid;
        $book->UPDATED_BY = $usrid;
        $book->save();
        $inv->update(['bookid'=>$book->id]);

        return back();
    }
}
