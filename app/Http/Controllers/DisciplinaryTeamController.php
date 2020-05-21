<?php

namespace App\Http\Controllers;

use App\APIError;
use App\DisciplinaryTeam;
use Illuminate\Http\Request;

class DisciplinaryTeamController extends Controller
{
    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    protected $badRequestStatus = 400;

    /**
     * create disciplinary team with id
     * @author adamu aliyu
     * adamualiyu199@gmail.com
     */
    public function create(Request $request) {
        $this->validate($request->all(), [
            'name' => 'required|string'
        ]);

        $disciplinaryTeam1= DisciplinaryTeam::whereName($request['name'])->first();
        if ($disciplinaryTeam1 != null) {
            $existError = new APIError;
            $existError->setStatus("400");
            $existError->setCode("DISCIPLINARY_TEAM_EXIST");
            $existError->setMessage("Disciplinary Team with name " . $request['name'] . " is duplicated");

            return response()->json($existError, $this->badRequestStatus);
        }

        $disciplinaryTeam = DisciplinaryTeam::create([
            'name' => $request->name
        ]);

        return response()->json($disciplinaryTeam, $this->createStatus);

    }

    /**
     * update disciplinary team with id
     * @author adamu aliyu
     * adamualiyu199@gmail.com
     */
    public function update(Request $request, $id) {
        $this->validate($request->all(), [
            'name' => 'required|string'
        ]);

        $disciplinaryTeam  = DisciplinaryTeam::find($id);

        if($disciplinaryTeam == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("DISCIPLINARY_TEAM_NOT_FOUND");
            $notFoundError->setMessage("Disciplinary Team with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        if ($request['name']!=$disciplinaryTeam->name){
          $disciplinaryTeam1= DisciplinaryTeam::whereName($request['name'])->first();
          if ($disciplinaryTeam1 != null) {
            $existError = new APIError;
            $existError->setStatus("400");
            $existError->setCode("DISCIPLINARY_TEAM_EXIST");
            $existError->setMessage("Disciplinary Team with name " . $request['name'] . " is duplicated");

            return response()->json($existError, $this->badRequestStatus);
          }
        }



        $disciplinaryTeam->update(
            $request->only([
                'name'
            ])
        );

        return response()->json($disciplinaryTeam, $this->successStatus);

    }


     /**
     * Find an existing  DisciplinaryTeam
     * @author Warren TABA
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
     * @author Warren TABA
     * @email fotiewarren50@gmail.com
     */

    public function get(Request $req){

        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
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
      *@author Warren TABA
      * @email fotiewarren50@gmail.com
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

