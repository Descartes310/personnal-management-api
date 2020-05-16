<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class BlogPostController extends Controller{

    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;

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
        if($blogPost == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("BLOG_POST_NOT_FOUND");
            $unauthorized->setMessage("blog_post id not found");

            return response()->json($unauthorized, 404); 
        }
        return response()->json($blogPost);
    }

    public function delete($id){

        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not found.");
        $blogPost->delete();
        return response()->json([]);
    }

     /**
     * create a blog_post 
     * @author Nebou Richie
     * @email richienebou@gmail.com
     */

    public function create(Request $request){
        
        $this->validate($request->all(), [
            'title' => 'required|string',
            'content' => 'string|required',
            'user_id' => 'integer|required|exists:App\User,id',
            'blog_category_id' => 'integer|required|exists:App\BlogCategory,id',
            'views'=>'integer'           
        ]);

        $data = $request->all();
        if($file = $request->file('image')){
            $request->validate(['image'=> 'image|mines:jpeg,png,jpg']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/blogs";
            $destinationPath = public_path($relativeDestination);
            $safeName = time() . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $data['image'] = "$relativeDestination/$safeName";
        }

        $blogPost = BlogPost::create($data);

        return response()->json($blogPost, $this->createStatus);
    }
    
     /**
     * Update a blog_post 
     * @author Nebou Riche
     * @email richienebou@gmail.com
     */

    public function update(Request $request, $id){

        $this->validate($request->all(), [
            'title' => 'required|string',
            'content' => 'string|required',
            'user_id' => 'integer|required|exists:App\User,id',
            'blog_category_id' => 'integer|required|exists:App\BlogCategory,id',
            'image' => 'nullable'
        ]);

        $blogPost = BlogPost::find($id);

        if($blogPost == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("BLOG_POST_NOT_FOUND");
            $notFoundError->setMessage("BLOG POST with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $blogPost->update(
            $request->only([ 
                'title', 
                'content', 
                'image',
                'views'
            ])
        );

        return response()->json($blogPost, $this->successStatus);

    }
}
    
    
