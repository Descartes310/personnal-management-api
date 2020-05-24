<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DisciplinaryTeamUser;
use App\APIError;

class DisciplinaryTeamUserController extends Controller
{
    public function get(Request $request)
    {
        $limit = $request->limit;
        $page = $request->page;

        if($limit || $page){
            $disc_team_users = DisciplinaryTeamUser::paginate($limit);
        }else{
            $disc_team_users = DisciplinaryteamUser:: all();
        }

        return response()->json($disc_team_users);
    }

    public function find($user_id)
    {
        $disc_team_users = DisciplinaryTeamUser::find($user_id);
        if($disc_team_users == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DISCIPLINARY_TEAM_USER_NOT_FOUND");
            $unauthorized->setMessage("No disciplinaryTeam User found with user_id $user_id");
            return response()->json($unauthorized, 404);
        }
        return response()->json($disc_team_users);
    }
}

