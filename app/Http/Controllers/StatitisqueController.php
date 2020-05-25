<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Career;
use App\ProSituation;
use App\APIError;
use App\User;
use App\Assignment;
use Carbon\Carbon;
use DB;
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

        $career = Career::where('user_id','=',2)->orderby('effective_date','asc')->get();
        return response()->json($career, 200);

    }

    //recuperation des la listes des affectations par mois dans la sructures.

    function getAssignByMonth(){
        $assigment = DB:: table('assignments')->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by years
            //return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
       // $assigment = Assignment::select(Assignment::raw('count(*) as total');
        return response()->json($assigment, 200);
    }
}
