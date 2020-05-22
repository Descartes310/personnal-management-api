<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Career;
use App\UserDivision;
use App\APIError;
use App\User;


class CareerController extends Controller
{
    //recuperatin de tout les contact
    public function get(Request $req)
    {
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($limit || $page) {
            $careers = Career::paginate($limit);
        } else {
            $careers = Career::all();
        }

        return response()->json($careers, 200);
    }

    public function find($id){

        $career = Career::find($id);

        if($career==null){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("CAREER_NOT_FOUND");
            $apiError->setMessage("no carrer found with id $id");
            $apiError->setErrors(['id' => ["this value is not exist"]]);
            return response()->json($apiError, 400);
        }

        return response()->json($career,200);
    }

    public function delete($id){

        $career = Career::find($id);

        if($career==null){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("CAREER_NOT_FOUND");
            $apiError->setMessage("no carrer found with id $id");
            $apiError->setErrors(['id' => ["this value is not exist"]]);

            return response()->json($apiError, 400);
        }

        $career->delete();
        return response()->json([]);
    }



    //create the career in controller

    public function create (Request $request){
        $request->validate([
            'pro_situation_id' => 'required',
            'user_id' => 'required',
            'effective_date' => 'nullable'
        ]);

        $user = User::find($request->user_id);
        if(!$user){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("no user found with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 400);

        }
        $career =  Career:: where('user_id','=',$request->user_id)->orderby('effective_date','desc')->first();
        if($career != null){
            if($career->pro_situation_id == $request->pro_situation_id){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("USER_CONSUME_PROSITUATION");
                $apiError->setMessage("this user have this pro_situation");
                return response()->json($apiError, 400); 
            }
            
        } 
        $data = $request->all();
        //$Udata['slug'] = str_replace(' ', '_', $request->user_id) . time();
        $career = Career::create([
            'user_id' => $request->user_id,
            'pro_situation_id'=> $request->pro_situation_id,
            'effective_date'=> $request->effective_date           
        ]);
        $division = UserDivision::where('user_id','=',$request->user_id)->first();
        //dd($division);
        if($division){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_DIVISION_ALREADY_EXIST");
            $apiError->setMessage("this user already belong to this division");
            $apiError->setErrors(['user_id' => ["this value already exist"]]);
            return response()->json($apiError, 400);
        }

        UserDivision::create([
            'user_id'=> $request->user_id,
            'division_id'=> $request->division_id,
            
        ]);
        return response()->json($career);
    }

    public function update(Request $request, $id){
        $career = Career::find($id);
        if($career == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CAREER_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'career id not existing']);

            return response()->json($apiError, 404);
        }
        
        $request->validate([
            'pro_situation_id' => 'required'
        ]);

        /* if(isset($request->user_id))
        {
            if(Career::find($request->user_id) == null)
            {//user_id not existing in table Career
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("CAREER_USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        } */

        $data = $request->all();
        //$data['slug'] = str_replace(' ', '_', $request->user_id) . time();
        $career->update($data);
        return response()->json($career);
    }
}
