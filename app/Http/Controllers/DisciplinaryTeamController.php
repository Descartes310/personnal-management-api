<?php

namespace App\Http\Controllers;
use App\APIError;
use App\DisciplinaryTeam;
use Illuminate\Http\Request;

class DisciplinaryTeamController extends Controller
{
     /**
     * Find an existing  DisciplinaryTeam
     * *Author Warren TABA
     */
    
    public function find($id){
        $disciplinaryteam = DisciplinaryTeam::find($id);
        if($disciplinaryteam == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("DISCIPLINARYTEAM_NOT_EXIST");
            $notexist->setMessage("DisciplinaryTeam does not exist with id $id.");
            
            return response()->json($notexist,404);
        }
        return response()->json($disciplinaryteam);
    }

    /**
     * Get All the DisciplinaryTeam
     * *Author Warren TABA
     */

    public function get(Request $req){
        $limit = $req->limit;
        $s = $req->s;
        $disciplinaryteam = DisciplinaryTeam::where('name','LIKE','%'.$s.'%')->paginate($limit);
        if($disciplinaryteam==null){
           $error_isempty = new APIError;
           $error_isempty->setStatus("404");
           $error_isempty->setCode("DISCIPLINARYTEAM_IS_EMPTY");
           $error_isempty->setMessage("DisciplinaryTeam is empty in Database.");
           
           return response()->json($error_isempty,404);
        }
        return response()->json($disciplinaryteam);
    }
    
     /**
      * Delete the choosen DisciplinaryTeam 
      *Author Warren TABA
      */

      public function delete($id){
        $disciplinaryteam = DisciplinaryTeam:: find($id);
        if($disciplinaryteam == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("DISCIPLINARYTEAM_NOT_EXIST");
            $notexist->setMessage("DISCIPLINARYTEAM id not found");

            return response()->json($notexist, 404);
        }
        $disciplinaryteam->delete($disciplinaryteam);
        return response()->json($disciplinaryteam);
      }
}