<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    public function get(Request $request){
        $divisions = Division::get();
        abort_if($divisions == null,"No division found", 404);
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
        abort_if($division == null,"No division found with id $id", 404);
        return response()->json($division);
    }

    public function delete($id){
        $division = Division::find($id);
        abort_if($division == null,"No division found with id $id", 404);
        $division->delete($division);
        return null;
    }      
}
