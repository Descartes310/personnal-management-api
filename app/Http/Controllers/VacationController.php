<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Vacation;
use App\Http\Controllers\Controller;


class VacationController extends Controller
{
    public function find($id){
		$vacat = Vacation::find($id);
        abort_if($vacat== null, 404, "vacation not found.");
        return response()->json($vacat);
	}


	public function get(Request $request) {
        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return Vacation::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                return Vacation::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return Vacation::paginate($limit);
            } else {
                return Vacation::all();
            }
        }
    }


	public function delete($id){
		$vacat = Vacation::find($id);
        abort_if($vacat == null, 404, "vacation not found.");
        $vacat->delete($vacat);
        return response()->json([]);
    }


}
