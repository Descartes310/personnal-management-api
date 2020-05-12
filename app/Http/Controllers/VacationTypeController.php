<?php

namespace App\Http\Controllers;

use App\APIError;
use App\VacationType;
use Illuminate\Http\Request;

class VacationTypeController extends Controller
{
     /**
     * @param name : string
     * @param description : string
     * @param days : interger
     * @author djionkoujunior@gmail.com
     */
    public function create(Request $request){

        $this->validate($request->all(), [
            'name' => 'required'
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
            $apiError->setCode("VACATIONTYPE_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'vacationtype id not existing']);

            return response()->json($apiError, 404);
        }

        $this->validate($request->all(), [
            'name' => 'required',
        ]);

        $data = $request->only([
            'name',
            'description',
            'days',
        ]);
        
       
        if($vacationtype == null) {
            $apiError = new APIError;
            $apiError->setStatus("400"); 
            $apiError->setCode("VACATIONTYPE_NOT_FOUND"); 
            $apiError->setMessage("no vacationtype foun with id ");
            return response()->json($apiError, 400);
        }

        $data['slug'] = str_replace(' ', '_', $request->name) . time();
        $vacationtype->update($data);
        return response()->json($vacationtype);
    }
    
}
