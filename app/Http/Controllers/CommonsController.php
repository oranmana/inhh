<?php

namespace App\Http\Controllers;

use App\Models\Common;
use Illuminate\Http\Request;

class CommonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $res)
    {
        $par = (isset($res['par']) ? $res['par'] : 0);
        $pars = ($par ? Common::where('id',$par)
            ->where('off',0)
            ->get() : 0);
        $commons = Common::where('par',$par)
            ->where('off',0)
            ->get();
        if ($commons->count())
            return view('commons.index',['pars'=> $pars, 'commons'=> $commons]);
//        return 'par=' . $par ;
    }

    public function readindex($id)
    {
        $common = Common::where('id',$id)->first();
        $tx = "$('#id').val('" . $common->id . "');
        $('#par').val('" . $common->par . "');
        $('#main').val('" . $common->main . "');
        $('#num').val('" . $common->num . "');
        $('#code').val('" . $common->code . "');
        $('#name').val('" . $common->name . "');
        $('#tname').val('" . $common->tname . "');
        $('#description').val('" . $common->description . "');
        $('#sub').val('" . $common->sub . "');
        $('#type').val('" . $common->type . "');
        $('#haschild').prop('checked'," . ($common->group?'true':'false') . ");
        $('#cat').val('" . $common->cat . "');
        $('#ref').val('" . $common->ref . "');
        $('#off').prop('checked'," . ($common->off?'true':'false') . ");
        $('#rec').show();
        ";		

        echo $tx;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Common  $common
     * @return \Illuminate\Http\Response
     */
    public function show(Common $common)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Common  $common
     * @return \Illuminate\Http\Response
     */
    public function edit(Common $common)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Common  $common
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Common $common)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Common  $common
     * @return \Illuminate\Http\Response
     */
    public function destroy(Common $common)
    {
        //
    }
}
