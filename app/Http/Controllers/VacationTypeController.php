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

     /**
     * @param name : string
     * @param description : string
     * @param days : interger
     * @author djionkoujunior@gmail.com
     */
    public function create(Request $request){

        $this->validate($request->all(), [
            'name' => 'required|unique:vacation_types'
        ]);
        $data = $request->only([
            'name',
            'description',
            'days'
        ]);
        //$data=$request->all();
        $data['slug'] = str_replace(' ', '_', $request->name) . time();
        $vacationtype = VacationType::create($data);
        return response()->json($vacationtype);
    }
     /**
     * 
     * @author djionkoujunior@gmail.com
     */
    public function update(Request $request,$id)
    {
        $vacationtype = VacationType::find($id);

        if($vacationtype == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATIONTYPE_NOT_FOUND");
            $apiError->setErrors(['id' => 'vacationtype id not existing']);

            return response()->json($apiError, 404);
        }

        $this->validate($request->all(), [
            'name' => 'required',
        ]);

        $vacationtypeTmp = VacationType::whereName($request->name)->first();
        
        if($vacationtype != $vacationtypeTmp && $vacationtypeTmp != null) {
            $apiError = new APIError;
            $apiError->setStatus("400"); 
            $apiError->setCode("VACATIONTYPE_ALREADY_EXISTS"); 
            $apiError->setMessage("no vacationtype foun with id ");
            return response()->json($apiError, 400);
        }

        $data = $request->only([
            'name',
            'description',
            'days',
        ]);

        $data['slug'] = str_replace(' ', '_', $request->name) . time();
        $vacationtype->update($data);
        return response()->json($vacationtype);
    }
    
}
