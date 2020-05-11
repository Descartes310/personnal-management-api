<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Division;
use App\APIError;


class DivisionController extends Controller
{
    
    public function create (Request $request){
        $request->validate([
            'name' => 'required'
        ]);
        
        if(isset($request->parent_id))
        {
            if(Division::find($request->parent_id) == null) 
            {//parent_id not existing in table division
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("DIVISION_PARENT_ID_NOT_FOUND");
                $apiError->setErrors(['parent_id' => 'parent_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        $data = $request->all();
        $data['slug'] = str_replace(' ', '_', $request->name) . time();
        $division = Division::create($data);
        return response()->json($division);
    }


    
    public function update(Request $request, $id){
        $division = Division::find($id);
        if($division == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("DIVISION_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'division id not existing']);

            return response()->json($apiError, 404);
        }
        
        $request->validate([
            'name' => 'required'
        ]);

        if(isset($request->parent_id))
        {
            if(Division::find($request->parent_id) == null)
            {//parent_id not existing in table division
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("DIVISION_PARENT_ID_NOT_FOUND");
                $apiError->setErrors(['parent_id' => 'parent_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        $data = $request->all();
        $data['slug'] = str_replace(' ', '_', $request->name) . time();
        $division->update($data);
        return response()->json($division);
    }
}
