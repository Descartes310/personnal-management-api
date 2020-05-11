<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\APIError;
use App\NoteCriteria;

class NoteCriteriaController extends Controller {

    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    protected $badRequestStatus = 400;

    public function create(Request $request) {
        $request->validate([
            'name' => 'string',
            'slug' => 'required|unique:note_criterias',
            'max_rate' => 'required|integer',
            'min_rate' => 'required|integer',
            'weight' => 'required|integer',
            'description' => 'string'
        ]);

        $noteCriteria = NoteCriteria::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'max_rate' =>$request ->max_rate,
            'min_rate' => $request->min_rate,
            'weight' => $request->weight,
            'description' => $request->description
        ]);

        return response()->json($noteCriteria, $this->createStatus);
    }


    public function update(Request $request, $id) {

        $this->validate($request->all(), [
            'name' => 'string',
            'slug' => 'string',
            'max_rate' => 'integer',
            'min_rate' => 'integer',
            'weight' => 'integer',
            'description' => 'string'
        ]);

        $noteCriteria = NoteCriteria::find($id);

        if($noteCriteria == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("NOT_FOUND_USER_ID");
            $notFoundError->setMessage("note criteria with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $slug = $request->slug;
        $noteCriteriaFound = NoteCriteria::whereSlug($slug)->first();

        if($noteCriteriaFound != null && $noteCriteriaFound != $noteCriteria) {
            $badRequestError = new APIError;
            $badRequestError->setStatus("400");
            $badRequestError->setCode("NOTE_CRITERIA_SLUG_ALREADY_EXIST");
            $badRequestError->setMessage("note criteria with slug " . $slug . " already exist");

            return response()->json($badRequestError, $this->badRequestStatus);
        }

        $noteCriteria->update(
            $request->only([ 
                'name', 
                'slug',
                'max_rate',
                'min_rate',
                'weight',
                'description'  
            ])
        );

        return response()->json($noteCriteria, $this->successStatus);

    }
    

}

