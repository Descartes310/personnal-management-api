<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\APIError;
use App\ProSituation;
    
class ProSituationController extends Controller {

    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;

    /**
     * find one submission with id
     * @author adamu aliyu
     * adamualiyu199@gmail.com
     */
    public function find($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("PRO_SITUATION_NOT_FOUND");
            $notFoundError->setMessage("pro_situation id not found");

            return response()->json($notFoundError, 404); 
        }
        return response()->json($prosituation);
    }

    /**
     * get all pro situations with specific parameters
     * @author adamu aliyu
     * adamualiyu199@gmail.com
     */
    public function get(Request $request){
      $limit = $request->limit;
      $s = $request->s; 
      $page = $request->page; 
      $prosituations = ProSituation::where('name','LIKE','%'.$s.'%')
                                     ->paginate($limit); 
      return response()->json($prosituations);
    }

    /**
     * delete one pro situation with id
     * @author adamu aliyu
     * adamualiyu199@gmail.com
     */
    public function delete($id){
        $prosituation = ProSituation::find($id);
        if($prosituation == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("PRO_SITUATION_NOT_FOUND");
            $notFoundError->setMessage("pro_situation id not found");

            return response()->json($notFoundError, 404); 
        }
        $prosituation->delete($prosituation);
        return response(null);
    }

    /**
     * Create a pro_situation with name, description and weight
     * @author Arléon Zemtsop
     * @email arleonzemtsop@gmail.com
     */
    public function create(Request $request) {

        $this->validate($request->all(), [
            'name' => 'required|string',
            'description' => 'string',
            'weight' => 'required|integer|min:1|max:100'
        ]);

        $proSituation = ProSituation::create([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight
        ]);

        return response()->json($proSituation, $this->createStatus);

    }

    /**
     * Update a pro_situation with name, description or weight
     * @author Arléon Zemtsop
     * @email arleonzemtsop@gmail.com
     */
    public function update(Request $request, $id) {

        $this->validate($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'weight' => 'integer|min:1|max:100'
        ]);

        $proSituation = ProSituation::find($id);

        if($proSituation == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("NOT_FOUND_USER_ID");
            $notFoundError->setMessage("Professionnal situation with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $proSituation->update(
            $request->only([ 
                'name', 
                'description', 
                'weight'
            ])
        );

        return response()->json($proSituation, $this->successStatus);

    }

}

