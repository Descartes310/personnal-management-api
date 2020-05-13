<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\APIError;
use App\DisciplinaryTeam;

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
    
}
