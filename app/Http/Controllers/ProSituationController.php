<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProSituation;

class ProSituationController extends Controller
{
    //
    public function find($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            abort(404, "No group found with id $id");
        }
        return response()->json($prosituation);
    }

    public function get($limit=null , $s=null){
       
        $prosituations = ProSituation::where('name','LIKE','%'.$s.'%')->paginate($limit); 
        return response()->json($prosituations);
    }
    
   


    public function delete($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            abort(404, "No group found with id $id");
        }
        $prosituation->delete($prosituation);
        return response(null);
    }

}
