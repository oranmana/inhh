<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountriesController extends Controller
{
    public function areas()
    {
        $places = Country::where('par','=',0)->orderBy('num')->get();
        return $places;
    }
    public function update(Request $req) {
        
    }
    public function save(Request $req) {
        $id = $req->input('id');
        $par = $req->input('par');
        $sub = $req->input('sub');
        $name = $req->input('name');
        $code = $req->input('code');
        $ref = $req->input('ref');

        $country = Country::findornew($id);
        $country->par = $par;
        $country->sub = $sub;
        $country->name = $name;
        $country->code = $code;
        $country->ref = $ref;

        if (strlen($name) > 2 && $par > 0) {
            $country->save();
            return 1;
        } else {
            return 0;
        }
    }
}
