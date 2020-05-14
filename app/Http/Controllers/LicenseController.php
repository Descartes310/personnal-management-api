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
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return License::paginate($limit);
            } else {
                return License::all();
            }
        }
    }



    public function find($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not found.");
        return response()->json($license);
    }


    public function changeStatus($id){

        $license = License::find($id)->first();
        abort_if($license == null, 404, "license not found.");
        $license->update(['is_active' => !$license->is_active]);
        return $license;
    }


    public function delete($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not found.");
        $license->delete();
        return response()->json([]);
    }
}
