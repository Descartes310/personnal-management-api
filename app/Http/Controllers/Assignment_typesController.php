<?php

namespace App\Http\Controllers;

use App\APIError;
use Illuminate\Http\Request;
use App\AssignmentType;
class Assignment_typesController extends Controller
{
    
    /**
     * Find an existing  AssignmentType
     */
    
    public function find($id){
        $assign_type = AssignmentType::find($id);
        if($assign_type == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("ASSIGNMENT_TYPE_NOT_EXIST");
            $notexist->setMessage("No AssignmentType with id $id.");
            
            return response()->json($notexist,404);
        }
        return response()->json($assign_type);
    }
    /**
     * Get All the AssignmentType
     */

     public function get(Request $req){
         $limit = $req->limit;
         $s = $req->s;
         $assign_type = AssignmentType::where('name','LIKE','%'.$s.'%')->paginate($limit);
         if($assign_type==null){
            $error_isempty = new APIError;
            $error_isempty->setStatus("404");
            $error_isempty->setCode("ASSIGNMENT_TYPE_IS_EMPTY");
            $error_isempty->setMessage("ASSIGNMENT_TYPE is empty in Database.");
            
            return response()->json($error_isempty,404);
         }
         return response()->json($assign_type);
     }

     /**
      * Delete the choosen AssignmentType 
      */

      public function delete($id){
        $assign_type = AssignmentType:: find($id);
        if($assign_type == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("ASSIGNMENT_TYPE_NOT_EXIST");
            $notexist->setMessage("AssignmentType id not found");

            return response()->json($notexist, 404);
        }
        $assign_type->delete($assign_type);
        return response()->json($assign_type);
      }
}
