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

        $s = $req->s;
<<<<<<< HEAD
        $page = $req->page; 
        $disciplinaryteam = DisciplinaryTeam::where('name','LIKE','%'.$s.'%')->paginate($limit);
        if($disciplinaryteam==null){
           $error_isempty = new APIError;
           $error_isempty->setStatus("404");
           $error_isempty->setCode("DISCIPLINARYTEAM_IS_EMPTY");
           $error_isempty->setMessage("DisciplinaryTeam is empty in Database.");
           
           return response()->json($error_isempty,404);
=======
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
>>>>>>> c5527919731ae4c2263ffccea175338d0e53ae3e
        }

        if ($s) {
            if ($limit || $page) {
                $disciplinaryteams = DisciplinaryTeam::where('name','LIKE','%'.$s.'%')->paginate($limit);
            } else {
                $disciplinaryteams = DisciplinaryTeam::where('name','LIKE','%'.$s.'%')->get();
            }
        } else {
            if ($limit || $page) {
                $disciplinaryteams = DisciplinaryTeam::paginate($limit);
            } else {
                $disciplinaryteams = DisciplinaryTeam::all();
            }
        }

        return response()->json($disciplinaryteams);
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
        $disciplinaryteam->delete();
        return response(null);
      }
}
