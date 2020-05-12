<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\DB;
use App\APIError;


class DivisionController extends Controller
{
    public function get(Request $request){
        $divisions = Division::get();
        if($divisions ==null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DIVISION_NOT_FOUND");
            $unauthorized->setMessage("any division not found in your database");
            return response()->json($unauthorized, 404); 
        }
        $page = $request->get('page');
        $limit =$request->get('limit');
        $s = $request->get('s');
        if($page!=null && $limit==null && $s==null){
            return response()->json(Division::paginate($page));
        }
        if ($limit!=null && $page==null && $s==null) {
            return response()->json(Division::take($limit)->get());
        }
        if ($s!=null && $page==null && $limit==null) {
            $result = DB::table('divisions')
                        ->where('name', 'like', "$s%")
                        ->get();
            return $result;
        }
        if ($page!=null && $limit!=null) {
            return response()->json(Division::paginate($page)->take($limit));
        }
        if ($page!=null && $s!=null) {
            $result = DB::table('divisions')
                        ->where('name', 'like', "$s%")
                        ->paginate($page);
            return $result;
        }
        if ($limit!=null && $s!=null) {
            $result = DB::table('divisions')
                        ->where('name', 'like', "$s%")
                        ->take($limit)
                        ->get();
            return $result;
        }
        if ($page!=null && $limit!=null && $s!=null) {
            $result = DB::table('divisions')
                        ->where('name', 'like', "$s%")
                        ->paginate($page)
                        ->take($limit)
                        ->get();
            return $result;
        }
        else{
            return response()->json($divisions);
        }
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
