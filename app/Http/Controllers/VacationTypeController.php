<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VacationType;
use App\APIError;

class VacationTypeController extends Controller
{
    //
    /**
     * Delete VacationType
     */
    public function delete(Request $request, $id){
        $vacationtype = VacationType::find($id);
        if($vacationtype==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATION_TYPE_NOT_FOUND");
            $apiError->setMessage("page does not exist");
            return response()->json($apiError, 404);
        }
        $vacationtype = VacationType::findOrFail($id);
        $vacationtype->delete();
        return response(null);
    }

     /**
     * Show VacationType
     */
    public function find($id){
        $vacationtype= VacationType::find($id);
        if($vacationtype==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATION_TYPE_NOT_FOUND");
            $apiError->setMessage("vacation type does not exist");
            return response()->json($apiError, 404);
        }
        return $vacationtype ;
    }

    /**
     * show all VacationType
     */
    public function get(Request $req) {
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $vacationTypes = VacationType::where('name', 'LIKE', '%' . $s . '%')->orWhere('description', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $vacationTypes = VacationType::where('name', 'LIKE', '%' . $s . '%')->orWhere('description', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $vacationTypes = VacationType::paginate($limit);
            } else {
                $vacationTypes = VacationType::all();
            }
        }

        return response()->json($vacationTypes);
    }

}

