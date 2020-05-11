<?php

namespace App\Http\Controllers;

use App\APIError;
use App\NoteCriteria;
use Illuminate\Http\Request;

class NoteCriteriaController extends Controller
{
     /**
     * Find an existing  NoteCriteria
     * *Author Warren TABA
     */
    
    public function find($id){
        $notecriteria = NoteCriteria::find($id);
        if($notecriteria == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("NOTCRITERIA_NOT_EXIST");
            $notexist->setMessage("NOTECRITERIA does not exist with id $id.");
            
            return response()->json($notexist,404);
        }
        return response()->json($notecriteria);
    }

    /**
     * Get All the NoteCriteria
     * *Author Warren TABA
     */

    public function get(Request $req){
        $limit = $req->limit;
        $s = $req->s;
        $notecriteria = NoteCriteria::where('name','LIKE','%'.$s.'%')->paginate($limit);
        if($notecriteria==null){
           $error_isempty = new APIError;
           $error_isempty->setStatus("404");
           $error_isempty->setCode("NOTECRITERIA_IS_EMPTY");
           $error_isempty->setMessage("NOTECRITERIA is empty in Database.");
           
           return response()->json($error_isempty,404);
        }
        return response()->json($notecriteria);
    }
    
     /**
      * Delete the choosen NoteCriteria 
      *Author Warren TABA
      */

      public function delete($id){
        $notecriteria = NoteCriteria:: find($id);
        if($notecriteria == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("NOTECRITERIA_NOT_EXIST");
            $notexist->setMessage("NOTECRITERIA id not found");

            return response()->json($notexist, 404);
        }
        $notecriteria->delete($notecriteria);
        return response()->json($notecriteria);
      }
}
