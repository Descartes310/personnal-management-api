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

