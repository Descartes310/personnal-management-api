<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\License;
/**
 * 
 * @author tchamou ramses
 * 
 */
class LicenseTypeController extends Controller
{

    public function saveLicenseType(Request $request){
        return response()->json($request);
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'days' => 'email',        
        ]);
        $file = $request->file('picture');
        $path = null;
        if($file != null){
            $request->validate(['picture'=>'picture|mimes: jpeg,jpg,png,svg']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/Licenses";
            $destinationPath = public_path($relativeDestination);
            $safeName = str_replace(' ','_',$request->name).time().'.'.$extension;
            $file->move($destinationPath, $safeName);
            $path = url("$relativeDestination/$safeName");
        }
        $License = License::create([
            'name' => $request->name,
            'slug' => $request->type,
            'description' => $request->nature,
            'days' => $request->email,     
        ]);

        
        return response()->json($License);
    }

    public function updateLicense(Request $request, $id){
        $request->validate([
            'name' => 'string',
            'slug' => 'string',
            'description' => 'text',
            'days' => 'integer',
        ]);
        $License = License::find($id);

        if($License != null){
            $path = null;
           
            if($file = $request->file('picture')){
                $request->validate(['picture'=>'picture|mimes: jpeg,jpg,png,svg']);
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/Licenses";
                $destinationPath = public_path($relativeDestination);
                $safeName = str_replace(' ','_',$request->email).time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                //Delete old License picture if exxists
                if ($License->picture) {
                    $oldpicturePath = str_replace(url('/'), public_path(), $License->picture);
                    if (file_exists($oldpicturePath)) {
                        @unlink($oldpicturePath);
                    }
                }
                $License->picture = url("$relativeDestination/$safeName");
            }
          
            $License->update($request->only([
                'name',
                'slug',
                'description',
                'days',
            ]));
  
          return response()->json($License);
        } else {
            $errorcode = new APIError;
            $errorcode->setStatus("404");
            $errorcode->setCode("CON_ERROR");
            $errorcode->setMessage("The License whit id ".$id." does not exist");

            return response()->json($errorcode, 404);
        }
    }

    
}
