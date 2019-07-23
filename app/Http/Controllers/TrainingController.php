<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Training;

class TrainingController extends Controller
{
    public function index($year=0,$type=0) {
        if (! $year) $year = date('Y');
        $trainings = Training::whereYear('ondate',$year)
            ->where(function($q) use ($type) {
                if($type > 0) return $q->where('category_id',$type);
            })->orderBy('code','desc')->get();
        return view('training.index',['year'=>$year,'type'=>$type, 'trainings'=>$trainings]);
    }
    public function show($trainid) {
        $train = Training::findorfail($trainid);
        return view('training.show',['trainid'=>$trainid, 'train'=>$train]);
    }
    public function addlist(Res $req) {
        $validated = $req->validate([
            'trainid' => 'required|min:1',
            'emplist' => 'required|array',
        ]);
        
    }
}
