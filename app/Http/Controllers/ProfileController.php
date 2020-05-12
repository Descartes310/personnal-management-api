<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use Illuminate\Support\Facades\DB;
use App\APIError;


class ProfileController extends Controller{
    /**
     * 
     * @author jiozangtheophane@gmail.com
     */
    public function get(Request $req){
        $limit = $req->limit;
        $s = $req->s; 
        $page = $req->page; 
        $profile = Profile::where('name','LIKE','%'.$s.'%')->paginate($limit);
        return response() ->json($profile); 
    }
    /**
     * 
     * @author jiozangtheophane@gmail.com
     */
    public function find($id){
     
        $profile = Profile::find($id);
        if($profile == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("FIND_PROFILE");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);
            
        }
        return response()->json($profile);
    }
    /**
     * 
     * @author jiozangtheophane@gmail.com
     */
    public function delete($id){

        $profile = Profile::find($id);
        if($profile == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DELETE_PROFILE");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);
        }
        else{
            $profile->delete($profile);
        }
        $profile = Profile::get();
        return response()->json($profile);
    }
}
