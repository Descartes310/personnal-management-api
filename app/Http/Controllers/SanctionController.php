<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use \Carbon\Carbon;
use App\Http\Controllers\Controller;

class SanctionController extends Controller{
    public function get(){
            
        $sanction = Sanction::get();
        return response()->json($sanction);
    }
 
    public function find($id){
            
        $sanction = Sanction::find($id);
        if($sanction == null){
            abort('id introuvable', 404);
        }
        return response()->json($sanction);
    }

    public function delete($id){

        $sanction = Sanction::find($id);
        if($sanction == null){
            abort('id introuvable', 404);
        }
        else{
            $sanction->delete($sanction);
        }
        $sanction = Sanction::get();
        return response()->json($sanction);
    }
}
