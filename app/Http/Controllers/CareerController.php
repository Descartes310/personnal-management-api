<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Career;
use App\APIError;
class CareerController extends Controller
{
    //recuperatin de tout les contact
    public function get(Request $request)
    {
        //recuperation de toutes les career dans la base de donnee
        $limit=$request->limit;
        
        //aucun parametre n'est present
        if(!$limit){
            $career = DB::table('careers')->orderby('id','desc')->get();    
        }
        else{
            $career = DB::table('careers')->paginate($limit);   
        }  

        return response()->json($career,200);
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
