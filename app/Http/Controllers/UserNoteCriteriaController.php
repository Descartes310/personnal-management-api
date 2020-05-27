<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\UserNoteCriteria;
use App\User;
use App\NoteCriteria;

class UserNoteCriteriaController extends Controller
{
    //
    public function find($id){
        $UserNoteCriterias = UserNoteCriteria::find($id);
        if ($UserNoteCriterias == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("UserNoteCriteria_NOT_FOUND");
            $unauthorized->setMessage("Incorrect id or is not declared.");

            return response()->json($unauthorized, 404);
            // arrete le code et retourne une erreur, quand à abort, le 1er paramètre c'est le message et 2nd c'est le code http
        }
        return response()->json($UserNoteCriterias);
    }

    public function get(){
        $userNoteCriterias = UserNoteCriteria::all();
        $datas = [];
        foreach ($userNoteCriterias as $key => $userNote) {
            $user = User::findWithProfile($userNote->user_id);
            $noteCriteria = NoteCriteria::find($userNote->note_criteria_id);
            $userNote['user'] = $user;
            $userNote['note_criteria'] = $noteCriteria;
            array_push($datas, $userNote);
        }
        return response()->json($datas);
    }

    public function delete($id){
        $UserNoteCriterias = UserNoteCriteria::find($id);
        if($UserNoteCriterias == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("UserNoteCriteria_NOT_FOUND");
            $unauthorized->setMessage(" UserNoteCriteria Not found .");

            return response()->json($unauthorized, 404);
        }
        $UserNoteCriterias->delete($UserNoteCriterias);
        return response()->json($UserNoteCriterias);
    }

    public function save(Request $request){
        $request->validate([
            'user_id' => 'required',
            'note_criteria_id' => 'required',
            'score' => 'required',
            'appreciation' => 'required',
            'description' => 'required'
        ]);
       
        $UserNoteCriteria = UserNoteCriteria::create([
            'user_id' => $request->user_id,
            'note_criteria_id' => $request->note_criteria_id,
            'score' => $request->score,
            'appreciation' => $request->appreciation,
            'description' => $request->description
        ]);

        
        return response()->json($UserNoteCriteria);
    }

    public function update(Request $request, $id){
        
        $request->validate([
            'user_id' => 'required',
            'note_criteria_id' => 'required',
            'score' => 'required',
            'appreciation' => 'required',
            'description' => 'required'
        ]);
        $UserNoteCriteria = UserNoteCriteria::find($id);
        $UserNoteCriteria->update($request->only([
            'user_id',
            'note_criteria_id',
            'score',
            'appreciation',
            'description'
        ]));
  
        return response()->json($UserNoteCriteria);
       
    }
}
