<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\ProfileUpdate;
use App\Http\Controllers\Controller;


class ProfileUpdateController extends Controller{


    public function get(Request $request){
        
        $s = $request->s;
        $page = $request->page;
        $limit = null;
         
        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return ProfileUpdate::where('old_value', 'like', "%$s%")->orWhere('new_value', 'like', "%$s%")->orWhere('raison', 'like', "%$s%")->paginate($limit);
            } else {
                return ProfileUpdate::where('old_value', 'like', "%$s%")->orWhere('new_value', 'like', "%$s%")->orWhere('raison', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return ProfileUpdate::paginate($limit);
            } else {
                return ProfileUpdate::all();
            }
        }
    }



    public function find($id){

        $profileUpdate = ProfileUpdate::find($id);
        abort_if($profileUpdate == null, 404, "ProfileUpdate not found.");
        return response()->json($profileUpdate);
    }

    public function delete($id){

        $profileUpdate = ProfileUpdate::find($id);
        abort_if($profileUpdate == null, 404, "ProfileUpdate not found.");
        $profileUpdate->delete();
        return response()->json([]);
    }
}
