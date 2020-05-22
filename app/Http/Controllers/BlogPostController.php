<?php

namespace App\Http\Controllers;

use App\APIError;
use Illuminate\Http\Request;
use App\BlogPost;
use App\User;
use App\BlogComment;
use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BlogPostController extends Controller {


    public function get(Request $req){
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($limit || $page) {
            $blogPost = BlogPost::paginate($limit);
        } else {
            $blogPost = BlogPost::all();
        }
        return response()->json($blogPost, 200);

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

        $user = Auth::user();
        //abort_unless($user->isAbleTo('read-blog-post', $blogPost->slug), 403);
        //recuperation du users qui a fait le post 
        $userPost = User::find($blogPost->user_id);
        //$blog_comments =BlogComment::where('blog_post_id','=',$blogPost->id); 
        $blog_comments =BlogComment::where('blog_post_id','=',$blogPost->id)->get();
        $blogPost->increment('views');
        return response()->json([
            'blog_post' => [
                    'id' => $blogPost->id,
                    'title' => $blogPost->title,
                    'content' => $blogPost->content,
                    'views' => $blogPost->views,
                    'image' => url($blogPost->image),
                    'user_id' => $blogPost->user_id,
                    'blog_category_id' => $blogPost->blog_category_id,
                    'create_at' => $blogPost->create_at,
                    'update_at' => $blogPost->update_at,
                    'user_post' => $userPost,
                     'bog_comments' =>$blog_comments
                ],
            
        ]);
    }

    public function delete($id){

        $blogPost = BlogPost::find($id);
        if(!$blogPost){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BlogPOst_NOT_FOUND");
            $apiError->setMessage("no blogPost found with id $request->$id");
            $apiError->setErrors(['blog_post_id' => ["this value is not exist"]]);
            return response()->json($apiError, 404);
        }
        $user = Auth::user();
        $blogPost->delete();
        return response(null);
    }


    public function create(Request $request){

        $this->validate($request->all(), [
            'title' => 'required|string',
            'content' => 'string|required',
            'blog_category_id' => 'integer|required|exists:App\BlogCategory,id',
        ]);

        $user = Auth::user();

        $data = $request->only('title', 'content', 'blog_category_id');
        $data['user_id'] = $user->id;
        $data['views'] = 0;
        //$data['slug'] = Str::slug($request->title) . time();
        $data['image'] = $this->uploadSingleFile($request, 'image', 'blogs', ['image', 'mimes:jpeg,png,jpg']);

        $blogPost = BlogPost::create($data);

        $team = Team::create([
            'name' => $blogPost->slug,
            'display_name' => "Team to handle BlogPost - $blogPost->title"
        ]);

        $blogPostPermissions = [
            'read-blog-post',
            'update-blog-post',
            'delete-blog-post'
        ];

        $user->attachPermissions($blogPostPermissions, $team);

        return response()->json($blogPost, 201);
    }


    public function update(Request $request, $id){

        $this->validate($request->all(), [
            'title' => 'required|string',
            'content' => 'string|required',
            'blog_category_id' => 'integer|required|exists:App\BlogCategory,id',
            'image' => 'nullable'
        ]);

        $blogPost = BlogPost::find($id);

        if ($blogPost == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("BLOG_POST_NOT_FOUND");
            $notFoundError->setMessage("BLOG POST with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $user = Auth::user();
        abort_unless($user->isAbleTo('update-blog-post', $blogPost->slug), 403);

        $data = $request->only(['title', 'content', 'blog_category_id']);
        $data['image'] = $this->uploadSingleFile($request, 'image', 'blogs', ['image', 'mimes:jpeg,png,jpg']);

        if ($data['image']) {
            @unlink(public_path($blogPost->image));
        }

        $blogPost->update($data);

        return response()->json($blogPost, 200);

    }
}


