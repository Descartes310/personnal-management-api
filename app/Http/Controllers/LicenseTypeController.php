<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\LicenseType;
use App\License;
use App\APIError;
use App\Http\Controllers\Controller;

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

    public function get() {
        return response()->json(LicenseType::get());
    }
    
// function find
    public function find($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license type not found.");
        return response()->json($licensetype);
	}

    public function delete($id){
        $licensetype = LicenseType::find($id);
        if($licensetype == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("LICENSE_TYPE_NOT_FOUND");
            $notFoundError->setMessage("license_type id not found");

            return response()->json($notFoundError, 404);
        }
        
        $licensetype->delete();
        return response(null);
    }

    public function add(Request $request){
        $this->validate($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:license_types'
        ]);
        $data = $request->all();
        $licensetype = LicenseType::create($data);
        return response()->json($licensetype);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request->all(), [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $licensetype = LicenseType::find($id);

        if($licensetype == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("LICENSE_TYPE_NOT_FOUND");
            $notFoundError->setMessage("LICENSE_TYPE type with id " . $id . " not found");
            return response()->json($notFoundError, 404);
        }

        $licensetype_tmp = LicenseType::whereName($request->name)->first();

        if($licensetype_tmp != null && $licensetype_tmp != $licensetype) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("400");
            $notFoundError->setCode("LICENSE_TYPE_ALREADY_EXISTS");
            $notFoundError->setMessage("Role aleady exists");
            return response()->json($notFoundError, 400);
        }


        $data = $request->only([
            'name',
            'slug',
            'days',
            'description'
        ]);

        $licensetype->update($data);
        return response()->json($licensetype);
    }

}
