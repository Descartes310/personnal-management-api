<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use Illuminate\Support\Facades\DB;
use App\APIError;

class BlogPostController extends Controller{
    public function create (Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        
        $data = $request->only([ 
            'user_id',
            'blog_category_id',
            'title',
            'content',
            'image',
            'views'
        ]);

        if(isset($request->user_id)){
            if(BlogPost::find($request->user_id) == null) {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("BLOG_POST_USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        if(isset($request->blog_category_id)){
            if(BlogPost::find($request->blog_category_id) == null) {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("BLOG_CATEGORY_ID_NOT_FOUND");
                $apiError->setErrors(['BLOG_CATEGORY_id' => 'blog_category_id not existing']);

                return response()->json($apiError, 400);
            }
            $blogPost = BlogPost::create($data);
            return response()->json($blogPost);
        
        }
    }
    public function update(Request $request, $id){
        $blogPost = BlogPost::find($id);
        if($blogPost == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BLOG_POST_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'blog_post id not existing']);
    
            return response()->json($apiError, 404);
        }
            
        $request->validate([
            'title' => 'required',
            'content' => 'required'
            ]);
            
        $data = $request->only([ 
            'user_id',
            'blog_category_id',
            'title',
            'content',
            'image',
            'views'
        ]);
    
    
        if(isset($request->user_id)){
            if(BlogPost::find($request->user_id) == null) {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("BLOG_POST_USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);
    
                return response()->json($apiError, 400);
            }
        }
    
        if(isset($request->blog_category_id)){
            if(BlogPost::find($request->blog_category_id) == null) {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("BLOG_CATEGORY_ID_NOT_FOUND");
                $apiError->setErrors(['BLOG_CATEGORY_id' => 'blog_category_id not existing']);
    
                return response()->json($apiError, 400);
            }
            $blogPost = BlogPost::create($data);
            return response()->json($blogPost);
        }
    }   
    
       
}
    
