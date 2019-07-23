<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $username = 'log_nm';
    public function index()
    {
        $users = User::where('state','>',0)
            ->orderBy('name')
            ->get();
        return view('users.index',['users'=> $users]);
    }
    public function readindex($id)
    {
        return 'This is readindex from '. $id ;
    }
    public function xreadindex($id)
    {
        $user = User::where('id',$id)->first();
        $tx = "$('#id').val('" . $user->id . "');
        $('#name').val('" . $user->name . "');
        $('#email').val('" . $user->email . "');
        $('#dir_id').val('" . $user->dir_id . "');
        $('#sapid').val('" . $user->sapid . "');
        $('#eagle').val('" . $user->eagle . "');
        $('#expiredate').val('" . $user->expiredate . "');
        $('#state').val('" . $user->state . "');
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
