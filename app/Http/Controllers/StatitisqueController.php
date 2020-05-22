<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Career;
use App\ProSituation;
use App\APIError;
use App\User;
class StatitisqueController extends Controller
{
    //return de table statistique of user

    function getDataSetUser($id)
    {
        $user = User::find($id);
        if(!$user){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("no user found with id $id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 400);
        }

        $career = Career::where('user_id','=',2)->get();
        return response()->json($career, 200);

    }
}
