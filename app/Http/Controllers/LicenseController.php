<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\License;
use App\Http\Controllers\Controller;


class LicenseController extends Controller{


    public function get(Request $request){


        $s = $request->s;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit) {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit) {
                return License::paginate($limit);
            } else {
                return License::all();
            }
        }
    }



    public function find($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not founded.");
        return response()->json($license);
    }


    public function changeStatus($id){

        $license = License::find($id)->first();
        abort_if($license == null, 404, "license not founded.");
        $license->update(['is_active' => !$license->is_active]);
        return $license;
    }


    public function delete($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not founded.");
        $license->delete($license);
        return response()->json([]);
    }
}
