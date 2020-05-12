<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Profile;

class ProfileController extends Controller
{

    public function create(Request $request){
        $request->validate([
            'name' => 'required',
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
    }

    
}
