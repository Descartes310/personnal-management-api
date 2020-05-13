<?php

namespace App\Http\Controllers;
use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\Vacation;
use App\Http\Controllers\Controller;


class VacationController extends Controller
{
    public function find($id){
		$vacat = Vacation::find($id);
        abort_if($vacat== null, 404, "vacation not founded.");
        return response()->json($vacat);
	}


	public function get(Request $request){
		$s=$request->s;
        $limit=$request->limit;
        $page=$request->page;

        if ($s) {
             if($limit){
                if ( $page) {
                    $vacat=vacation::where('raison', 'like', $s)->paginate($limit);
                    $pagenumber=$vacat->lastPage();
            
                    abort_if($pagenumber < $page, 404, "vacation page not founded.");
                    return Vacation::where('raison', 'like', $s)->paginate($limit);
                }

                $vacat=Vacation::where('raison', 'like', $s)->paginate($limit);
                return $vacat;
            }
            if ($page) {
                return Vacation::where('raison', 'like', $s)->paginate(5);
            }
            return Vacation::where('raison', 'like', $s)->get();
        }

        if($limit){

                if ( $page) {

                    $vacat=Vacation::paginate($limit);
                    $pagenumber=$vacat->lastPage();
                    abort_if($pagenumber < $page, 404, "vacation page not founded.");
                    return $vacat;
                }
                
                $vacat=Vacation::paginate($limit);
                return $vacat->lastPage();
            }

        if ($page) {
           return Vacation::paginate(5);
        }
        return Vacation::all();

    }



	

	public function delete($id){
		$vacat = Vacation::find($id);
        abort_if($vacat == null, 404, "vacation does not founded.");
        $vacat->delete($vacat);
        return response()->json([]);
    }


}
