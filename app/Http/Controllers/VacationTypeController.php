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
        return 200;
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
    public function get(Request $request) {

        $limit = $request->limit;
        $page = $request->page;
        $s = $request->s;

        $vacationtypes = VacationType::where('raison', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);

        return response()->json($vacationtypes);
    }

}

