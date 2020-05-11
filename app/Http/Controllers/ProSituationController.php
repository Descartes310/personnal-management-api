<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProSituation;
use App\APIError;

class ProSituationController extends Controller
{
    /**
     * find one submission with id
     * @author adamu aliyu
     */
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

    /**
     * get all submissions with specific parameters
     * @author adamu aliyu
     */
    public function get(Request $request){
      $limit = $request->limit;
      $s = $request->s; 
      $page = $request->page; 
      $prosituations = ProSituation::where('name','LIKE','%'.$s.'%')
                                     ->paginate($limit); 
      return response()->json($prosituations);
    }

    /**
     * delete one  submission with id
     * @author adamu aliyu
     */
    public function delete($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("PRO_SITUATION_NOT_FOUND");
            $unauthorized->setMessage("pro_situation id not found");

            return response()->json($unauthorized, 404); 
        }
        $prosituation->delete($prosituation);
        return response(null);
    }

}
