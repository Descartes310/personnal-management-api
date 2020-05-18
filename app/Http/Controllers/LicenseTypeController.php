<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\APIError;
use App\License;
/**
 * 
 * @author whitney houston
 * 
 */
class LicenseTypeController extends Controller
{

    // function save
    public function saveLicenseType(Request $request){
        return response()->json($request);
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'days' => 'email',        
        ]);
        
        $License = License::create([
            'name' => $request->name,
            'slug' => $request->type,
            'description' => $request->nature,
            'days' => $request->email,     
        ]);

        
        return response()->json($License);
    }

    // function update
    public function updateLicenseType(Request $request, $id){
        $request->validate([
            'name' => 'string',
            'slug' => 'string',
            'description' => 'text',
            'days' => 'integer',
        ]);
        $License = License::find($id);

        if($License != null){
            $path = null;

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
            $errorcode->setMessage("The License with id ".$id." does not exist");

            return response()->json($errorcode, 404);
        }
    }
    
// function find
    public function find($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license type not found.");
        return response()->json($licensetype);
	}

}
