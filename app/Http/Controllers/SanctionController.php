<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use App\Role;
use App\User;
use App\Sanction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use \Carbon\Carbon;
use App\Http\Controllers\Controller;

class SanctionController extends Controller{
    public function get(Request $req){
        $limit = $req->limit;
        $s = $req->s; 
        $page = $req->page; 
        $sanction = Sanction::where('id','LIKE','%'.$s.'%')->paginate($limit);
        return response() ->json($sanction); 
    }
 
    public function find($id){
     
        $sanction = Sanction::find($id);
        if($sanction == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("FIND_SANCTION");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);
            
        }
        return response()->json($sanction);
    }

    public function delete($id){

        $sanction = Sanction::find($id);
        if($sanction == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DELETE_SANCTION");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);
        }
        else{
            $sanction->delete($sanction);
        }
        $sanction = Sanction::get();
        return response()->json($sanction);
    }
}
