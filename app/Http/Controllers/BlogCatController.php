<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogCategory;
use Illuminate\Support\Facades\DB;
use App\APIError;

class BlogCatController extends Controller
{
    public function create (Request $request){
        $request->validate([
            'title' => 'required'
        ]);
        
        $data = $request->only([  
            'title', 
            'description'
        ]);

        $blogcat = BlogCategory::create($data);
        return response()->json($blogcat);
    }


    
    public function update(Request $request, $id){
        $blogcat = BlogCategory::find($id);
        if($blogcat == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BLOGCATEGORY_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'blog category id not existing']);

            return response()->json($apiError, 404);
        }
        
        $request->validate([
            'title' => 'required'
        ]);

        $data = $request->only([ 
            'title', 
            'description'
        ]);

        $blogcat->update($data);
        return response()->json($blogcat);
    }
}
