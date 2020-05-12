<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\DB;
use App\APIError;


class DivisionController extends Controller
{
    
    public function create (Request $request){
        $request->validate([
            'name' => 'required'
        ]);
        
        $data = $request->only([ 
            'parent_id', 
            'name', 
            'description'
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

        //$data = $request->all()
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

        $data = $request->only([ 
            'parent_id', 
            'name', 
            'description'
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

    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s; 
        $page = $request->page; 
        $divisions = Division::where('name','LIKE','%'.$s.'%')
                                       ->paginate($limit); 
        return response()->json($divisions);
      }

    public function find($id){
        $division = Division::find($id);
        if($division ==null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DIVISION_NOT_FOUND");
            $unauthorized->setMessage("No division found with id $id");
            return response()->json($unauthorized, 404); 
        }
        return response()->json($division);
    }

    public function delete($id){
        $division = Division::find($id);
        if($division ==null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DIVISION_NOT_FOUND");
            $unauthorized->setMessage("No division found with id $id");
            return response()->json($unauthorized, 404); 
        }
        $division->delete($division);
        return null;
    }   
}
