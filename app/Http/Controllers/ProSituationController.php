<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\APIError;
use App\ProSituation;

class ProSituationController extends Controller
{
    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    
    /**
     * Create a pro_situation with name, description and weight
     * @author Arléon Zemtsop
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
            'weight' => $request->weight,
        ]);

        return response()->json($proSituation, $this->createStatus);

    }

    /**
     * Update a pro_situation with name, description or weight
     * @author Arléon Zemtsop
     */
    public function update(Request $request, $id) {

        $this->validate($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'weight' => 'integer|min:1|max:100'
        ]);

        $proSituation = ProSituation::find($id);

        if($proSituation == null)
            abort($this->notFoundStatus, "Professionnal situation not found");

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

