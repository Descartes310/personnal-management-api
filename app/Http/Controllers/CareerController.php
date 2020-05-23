<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Career;
use App\APIError;


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

        if(isset($request->user_id))
        {
            if(Career::find($request->user_id) == null)
            {//user_id not existing in table Career
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("CAREER_user_id_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        $data = $request->all();
        $data['slug'] = str_replace(' ', '_', $request->user_id) . time();
        $career = Career::create($data);
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

        if(isset($request->user_id))
        {
            if(Career::find($request->user_id) == null)
            {//user_id not existing in table Career
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("CAREER_USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        $data = $request->all();
        $data['slug'] = str_replace(' ', '_', $request->user_id) . time();
        $career->update($data);
        return response()->json($career);
    }
}
