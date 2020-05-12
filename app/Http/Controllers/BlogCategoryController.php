<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogCategory;
use App\APIError;

class BlogCategoryController extends Controller
{
    protected $succesStatus = 200;
    protected $notFoundStatus = 404;
    protected $badRequest = 200;

    
    /**
     * delete a blog Category
     * @author Brell Sanwouo
     */

    public function delete ($id){
        $blogCategory = BlogCategory::find($id);
        if(!$blogCategory){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("BLOG_CATEGORY_NOT_FOUND");
            $unauthorized->setMessage("blog Category id not found in database.");

            return response()->json($unauthorized, 404);
        }

        $blogCategory->delete();
        $unauthorized = new APIError;
        $unauthorized->setStatus("200");
        $unauthorized->setCode("BLOG_CATEGORY_DELETED");
        $unauthorized->setMessage("blogCategory deleted succesfully in database.");

        return response()->json($unauthorized, 404);
    }

    /**
     * find a specific blog Category 
     * @author Brell Sanwouo
     */
    public function find($id){
        $blogCategory = BlogCategory::find($id);
        if($blogCategory == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("BLOG_CATEGORY_NOT_FOUND");
            $unauthorized->setMessage("blog Category id not found in database.");

            return response()->json($unauthorized, 404);
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
        $blogCategory = BlogCategory::where('title', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);

        return response()->json($blogCategory);
    }
}
