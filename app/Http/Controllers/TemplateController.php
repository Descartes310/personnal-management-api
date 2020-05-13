<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\APIError;


class TemplateController extends Controller
{

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


    /**
     *  @author jiozangtheophane@gmail.com
    */
    public function create (Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $data = $request->only([
            'title',
            'content',
            'type'
        ]);

        $template = Template::create($data);
        return response()->json($template);
    }

    /**
     * @author jiozangtheophane@gmail.com
     */
    public function update(Request $request, $id){
        $template = Template::find($id);
        if($template == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("TEMPLATE_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'template id not existing']);

            return response()->json($apiError, 404);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $data = $request->only([
            'title',
            'content',
            'type'
        ]);

        $template->update($data);
        return response()->json($template);
    }
}
