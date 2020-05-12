<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\APIError;


class TemplateController extends Controller
{
    //

      
    /**
     * Delete template
     */
    public function delete(Request $request, $id){
        $template = Template::find($id);
        if($template==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("TEMPLATE_PAGE_NOT_FOUND"); 
            $apiError->setMessage("page does not exist"); 
            return response()->json($apiError, 404);       
        }
        $template = Template::findOrFail($id);
        $template->delete();
        return 200;
    }

  
    /**
     * Show template
     */
    public function find($id){
        $template= Template::find($id); 
        if($template==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("TEMPLATE_PAGE_NOT_FOUND"); 
            $apiError->setMessage("page does not exist"); 
            return response()->json($apiError, 404); 
        }           
        return $template ;
    }
   
    /**
     * show all Templates
     */
    public function get(Request $request) {

        $limit = $request->limit;
        $page = $request->page;
        $s = $request->s;
        $templates = Template::where('name','LIKE','%'.$s.'%')
                               ->paginate($limit); 
        return response()->json($templates);
      
    }
     
}

