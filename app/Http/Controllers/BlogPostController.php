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
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return BlogPost::where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->paginate($limit);
            } else {
                return BlogPost::where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return BlogPost::paginate($limit);
            } else {
                return BlogPost::all();
            }
        }
    }



    public function find($id){

        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not found.");
        return response()->json($blogPost);
    }

    public function delete($id){

        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not found.");
        $blogPost->delete();
        return response()->json([]);
    }
}
