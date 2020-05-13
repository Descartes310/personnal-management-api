<?php

namespace App\Http\Controllers;
use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\LicenseType;
use App\Http\Controllers\Controller;

class LicenseTypeController extends Controller
{
    public function find($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license  type not founded.");
        return response()->json($licensetype);
	}


	public function get(Request $request){
		$s=$request->s;
        $limit=$request->limit;
        $page=$request->page;

        if ($s) {
             if($limit){
                if ( $page) {
                    $licensetype=LicenseType::where('name', 'like', $s)->paginate($limit);
                    $pagenumber=$licensetype->lastPage();
            
                    abort_if($pagenumber < $page, 404, "license  type page not founded.");
                    return LicenseType::where('name', 'like', $s)->paginate($limit);
                }

                $licensetype=LicenseType::where('name', 'like', $s)->paginate($limit);
                return $licensetype;
            }
            if ($page) {
                return LicenseType::where('name', 'like', $s)->paginate(5);
            }
            return LicenseType::where('name', 'like', $s)->get();
        }

        if($limit){

                if ( $page) {

                    $licensetype=LicenseType::paginate($limit);
                    $pagenumber=$licensetype->lastPage();
                    abort_if($pagenumber < $page, 404, "license  type page not founded.");
                    return $licensetype;
                }
                
                $licensetype=LicenseType::paginate($limit);
                return $licensetype->lastPage();
            }

        if ($page) {
           return LicenseType::paginate(15);
        }
        return LicenseType::all();

    }





	public function delete($id){
		$licensetype = LicenseType::find($id);
        abort_if($licensetype == null, 404, "license type not founded.");
        $licensetype->delete($licensetype);
        return response()->json([]);
    }

}
