<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Controllers\Controller;


class BlogPostController extends Controller{


    public function get(Request $request){

        $s = $request->s;
        $limit = null;
        
        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit) {
                return BlogPost::where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->paginate($limit);
            } else {
                return BlogPost::where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->get();
            }
        } else {
            if ($limit) {
                return BlogPost::paginate($limit);
            } else {
                return BlogPost::all();
            }
        }
    }



    public function find($id){

        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not founded.");
        return response()->json($blogPost);
    }

    public function delete($id){

        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not founded.");
        $blogPost->delete($blogPost);
        return response()->json([]);
    }
}
