<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\LicenseType;
use App\Http\Controllers\Controller;

class LicenseTypeController extends Controller
{
    public function find($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license type not found.");
        return response()->json($licensetype);
	}


	public function get(Request $request){
        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return LicenseType::where('name', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                return LicenseType::where('name', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return LicenseType::paginate($limit);
            } else {
                return LicenseType::all();
            }
        }
    }





	public function delete($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license type not founded.");
        $licensetype->delete();
        return response()->json([]);
    }


    public function add(Request $request){
        $this->validate($request->al(), [
            'name' => 'required',
            'slug' => 'required|unique:license_types'
        ]);
        $data = $request->all();
        $licensetype = LicenseType::create($data);
        return response()->json($licensetype);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request->al(), [
            'name' => 'required',
            'slug' => 'required|unique:license_types'
        ]);
        $licensetype = LicenseType::find($id);

        abort_if($licensetype == null, 404, "license type not founded.");


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
