<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\APIError;
use Illuminate\Support\Str;


class DivisionController extends Controller
{

    public function create (Request $request){
        $this->validate($request->all(), [
            'name' => 'required'
        ]);

        $data = $request->only([
            'name',
            'description'
        ]);

        if($request->parent_id)
        {
            if(Division::find($request->parent_id) == null)
            {//parent_id not existing in table division
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("DIVISION_PARENT_ID_NOT_FOUND");
                $apiError->setErrors(['parent_id' => 'parent_id not existing']);

                return response()->json($apiError, 400);
            } else {
                $data['parent_id'] = $request->parent_id;
            }
        }

        $data['slug'] = Str::slug($request->name) . time();
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

        $this->validate($request->all(), [
            'name' => 'required'
        ]);

        $data = $request->only([
            'name',
            'description'
        ]);

        if($request->parent_id)
        {
            if(Division::find($request->parent_id) == null)
            {//parent_id not existing in table division
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("DIVISION_PARENT_ID_NOT_FOUND");
                $apiError->setErrors(['parent_id' => 'parent_id not existing']);

                return response()->json($apiError, 400);
            } else {
                $data['parent_id'] = $request->parent_id;
            }
        }

        $division->update($data);
        return response()->json($division);
    }

    public function get(Request $req) {
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $divisions = Division::where('name', 'LIKE', '%' . $s . '%')->with('division')->paginate($limit);
            } else {
                $divisions = Division::where('name', 'LIKE', '%' . $s . '%')->with('division')->get();
            }
        } else {
            if ($limit || $page) {
                $divisions = Division::with('division')->paginate($limit);
            } else {
                $divisions = Division::with('division')->get();
            }
        }
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
        $division->division;
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
