<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogCategory;
use App\APIError;
use App\BlogPost;
use App\User;

class BlogCategoryController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blog_categories'
        ]);

        $data = $request->only([
            'title',
            'description'
        ]);

        $blogcat = BlogCategory::create($data);
        return response()->json($blogcat);
    }



    public function update(Request $request, $id)
    {
        $blogcat = BlogCategory::find($id);
        if ($blogcat == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BLOGCATEGORY_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'blog category id not existing']);

            return response()->json($apiError, 404);
        }

        $blogcat_tmp = BlogCategory::whereTitle($request->title)->first();

        if($blogcat_tmp != null && $blogcat_tmp != $blogcat) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("400");
            $notFoundError->setCode("BLOG_CAT_ALREADY_EXISTS");
            $notFoundError->setMessage("blof cat aleady exists");
            return response()->json($notFoundError, 400);
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


    /**
     * delete a blog Category

     */
    public function delete ($id){
        $blogCategory = BlogCategory::find($id);
        if(!$blogCategory){
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("BLOG_CATEGORY_NOT_FOUND");
            $notFound->setMessage("blog Category id not found in database.");

            return response()->json($notFound, 404);
        }

        $blogCategory->delete();

        return response()->json(null);
    }

    /**
     * find a specific blog Category
     * @author Brell Sanwouo
     */


    public function find($id){
        $blogCategory = BlogCategory::find($id);
        if($blogCategory == null){
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("BLOG_CATEGORY_NOT_FOUND");
            $notFound->setMessage("blog Category id not found in database.");

            return response()->json($notFound, 404);
        }
        return response()->json($blogCategory);
    }

    /**
     * get all blog categories with spécific value
     * @author Brell Sanwouo
     */

     public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s;
        $page = $request->page;
        $blogcats = BlogCategory::where('title', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);
//
        return response()->json($blogcats);
    }


}
