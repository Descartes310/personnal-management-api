<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\APIError;


class ProfileController extends Controller{
    /**
     *
     * @author jiozangtheophane@gmail.com
     */
    public function get(Request $req){
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $profiles = Profile::where('name', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $profiles = Profile::where('name', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $profiles = Profile::paginate($limit);
            } else {
                $profiles = Profile::all();
            }
        }

        return response() ->json($profiles);
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

        $profile->delete($profile);

        return response(null);
    }
}
