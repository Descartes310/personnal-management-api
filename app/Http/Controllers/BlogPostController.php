<?php

namespace App\Http\Controllers;

use App\APIError;
use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Permission;


class BlogPostController extends Controller {


    public function get(Request $request){

        $s = $request->s;
        $page = $request->page;
        $limit = null;


       // 'name' => $blogPost->slug

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        $user = Auth::user();

        if ($s) {
            if ($limit || $page) {
                return $user->getPermittedBlogPost('read-blog-post')->where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->paginate($limit);
            } else {
                return $user->getPermittedBlogPost('read-blog-post')->where('title', 'like', "%$s%")->orWhere('content', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return $user->getPermittedBlogPost('read-blog-post')->paginate($limit);
            } else {
                return $user->getPermittedBlogPost('read-blog-post')->get();
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
        $user = Auth::user();
        abort_unless($user->isAbleTo('read-blog-post', $blogPost->slug), 403);
        $blogPost->increment('views');
        return response()->json($blogPost);
    }

    public function delete($id){


       // 'name' => $blogPost->slug,
        $blogPost = BlogPost::find($id);
        abort_if($blogPost == null, 404, "BlogPost not found.");
        $user = Auth::user();
        abort_unless($user->isAbleTo('delete-blog-post', $blogPost->slug), 403);
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
        $data['slug'] = Str::slug($request->title) . time();
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
        $data['image'] = $this->uploadSingleFile(
            $request, 'image', 'blogs', ['image', 'mimes:jpeg,png,jpg']);

        if ($data['image']) {
            @unlink(public_path($blogPost->image));
        }

        $blogPost->update($data);

        return response()->json($blogPost, 200);

    }
}


