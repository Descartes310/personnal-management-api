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




}
