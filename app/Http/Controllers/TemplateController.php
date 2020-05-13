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
    public function get(Request $req) {
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $templates = Template::where('name', 'LIKE', '%' . $s . '%')
                                    ->orWhere('content', 'LIKE', '%' . $s . '%')
                                    ->orWhere('type', 'LIKE', '%' . $s . '%')
                                    ->paginate($limit);
            } else {
                $templates = Template::where('name', 'LIKE', '%' . $s . '%')
                                    ->orWhere('content', 'LIKE', '%' . $s . '%')
                                    ->orWhere('type', 'LIKE', '%' . $s . '%')
                                    ->get();
            }
        } else {
            if ($limit || $page) {
                $templates = Template::paginate($limit);
            } else {
                $templates = Template::all();
            }
        }

        return response()->json($templates);

    }


    /**
     *  @author jiozangtheophane@gmail.com
    */
    public function create (Request $request){
        $this->validate($request->all(), [
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

        $this->validate($request->all(), [
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
