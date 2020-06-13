<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogComment;
use App\User;
use App\BlogPost;
use App\APIError;
class BlogCommentController extends Controller
{
    //

    public function create(Request $request)
    {
        $this->validate($request->all(), [
            'user_id' => 'required',
            'blog_post_id' => 'required',
        ]);
        //on verifie si l'utilisateur et le post existe deja
        $user = User::find($request->user_id);
        if(!$user){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("no user found with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 404);
        }

        $blogPost = BlogPost::find($request->blog_post_id);
        if(!$blogPost){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BlogPOst_NOT_FOUND");
            $apiError->setMessage("no blogPost found with id $request->blog_post_id");
            $apiError->setErrors(['blog_post_id' => ["this value is not exist"]]);
            return response()->json($apiError, 404);
        }
        //

        //tout est correct
        $blogComment =BlogComment::create($request->all());
        return response()->json($blogComment, 200);
    }

    //suppression d'un commentaire
    public function delete($id)
    {
        $blogComment=BlogComment::find($id);
        if(!$blogComment){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BlogComment_NOT_EXIT");
            $apiError->setMessage("no blogCommet found with id $id");
            $apiError->setErrors(['blog_comment_id' => ["this value is not exist"]]);
            return response()->json($apiError, 404);
        }
        //tout ce passe bien
        $blogComment->delete();
        return response()->json(null,200);

    }
}
