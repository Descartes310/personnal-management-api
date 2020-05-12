<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\License;
use App\Http\Controllers\Controller;


class LicenseController extends Controller{


    public function get(Request $request){
        
        
        $s=$request->s;
        $limit=$request->limit;
        $page=$request->page;

        if ($s) {
             if($limit){
                if ( $page) {
                    $license=License::where('raison', 'like', $s)->paginate($limit);
                    $pagenumber=$license->lastPage();
            
                    abort_if($pagenumber < $page, 404, "license page not founded.");
                    return License::where('raison', 'like', $s)->paginate($limit);
                }

                $license=License::where('raison', 'like', $s)->paginate($limit);
                return $license;
            }
            if ($page) {
                return License::where('raison', 'like', $s)->paginate(10);
            }
            return License::where('raison', 'like', $s)->get();
        }

        if($limit){

                if ( $page) {

                    $licence=License::paginate($limit);
                    $pagenumber=$license->lastPage();
                    abort_if($pagenumber < $page, 404, "license page not founded.");
                    return $license;
                }
                
                $license=License::paginate($limit);
                return $license->lastPage();
            }

        if ($page) {
           return License::paginate(15);
        }
        return License::all();

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
