<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\APIError;
use App\Profile;

class ProfileController extends Controller
{

    public function create(Request $request){
        $request->validate([
            'name' => 'required',
=======
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


    public function create(Request $request){
        $request->validate([
            'name' => 'required|unique:profiles',
>>>>>>> 26ab1d3575a6f48aca079c18fb2cdf69084046bb
            'type' => 'required',
            'is_required' => 'required',
            'is_updatable' => 'required',
            'is_unique' => 'required',
        ]);

        $profile = Profile::create([
            'name' => $request->name,
            'type' => $request->type,
            'placeholder' => $request->placeholder,
            'is_required' => $request->is_required,
            'is_updatable' => $request->is_updatable,
            'slug' => $request->name.''.time(),
            'min' => $request->min,
            'max'  => $request->max,
            'step' => $request->step,
            'is_unique' => $request->is_unique,
            'default' => $request->default,
            'description' => $request->description
        ]);

        return response()->json($profile);
    }

    public function update(Request $request, $id){
        
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);
        $Profile = Profile::find($id);
        if($Profile != null){
            
            $Profile->update($request->only([
                'name',
                'type',
                'placeholder',
                'is_required',
                'is_updatable',
                'slug',
                'min',
                'max',
                'step',
                'is_unique',
                'default',
                'description'
            ]));
  
          return response()->json($Profile);
        } else {
            $errorcode = new APIError;
            $errorcode->setStatus("401");
            $errorcode->setCode("PRO_ERROR");
            $errorcode->setMessage("The profile with id ".$id." does not exist");

            return response()->json($errorcode, 401);
        }
<<<<<<< HEAD
    }

    
=======
    }    
>>>>>>> 26ab1d3575a6f48aca079c18fb2cdf69084046bb
}
