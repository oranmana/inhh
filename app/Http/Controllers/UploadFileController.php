<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    private $dess = array(0=>'test',1=>'attn');
    public function index(Request $req, $uid=0){
        if($req->ajax()) {
            return view('partials.uploadfile',['des'=>$uid])->render();    
        } else {
            return view('partials.uploadfile',['des'=>$uid]);
        }
     }
     public function showUploadFile(Request $request){
        $file = $request->file('text');

        //Display File Name
        $tx =  'File Name: '.$file->getClientOriginalName();
        $tx .=  '<br>';
     
        //Display File Extension
        $tx .= 'File Extension: '.$file->getClientOriginalExtension();
        $tx .= '<br>';
     
        //Display File Real Path
        $tx .= 'File Real Path: '.$file->getRealPath();
        $tx .= '<br>';
     
        //Display File Size
        $tx .= 'File Size: '.$file->getSize();
        $tx .= '<br>';
        $tx .= "<button onclick='window.history.go(-1);'>Back</a>";
        //Display File Mime Type
//        echo 'File Mime Type: '.$file->getMimeType();
     
        //Move Uploaded File
        $destinationPath = 'upload/'.$this->dess[$request->input('des')];
        $file->move($destinationPath,$file->getClientOriginalName());

        return $tx;
     }
}
