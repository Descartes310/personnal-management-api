<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProSituation;
use App\APIError;

class ProSituationController extends Controller
{
    public function find($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("PRO_SITUATION_FIND");
            $unauthorized->setMessage("pro_situation id not found");

            return response()->json($unauthorized, 404); 
        }
        return response()->json($prosituation);
    }

    public function get(Request $request){
      $limit = $request->limit;
      $s = $request->s; 
      $page = $request->page; 
      $prosituations = ProSituation::where('name','LIKE','%'.$s.'%')
                                     ->paginate($limit); 
      return response()->json($prosituations);
    }

    public function delete($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("PRO_SITUATION_DELETE");
            $unauthorized->setMessage("pro_situation id not found");

            return response()->json($unauthorized, 404); 
        }
        $prosituation->delete($prosituation);
        return response(null);
    }

}
