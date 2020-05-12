<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\APIError;
use App\NoteCriteria;

class NoteCriteriaController extends Controller {

    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    protected $badRequestStatus = 400;

    public function create(Request $request) {
        $request->validate([
            'name' => 'string|required',
            'max_rate' => 'required|integer',
            'min_rate' => 'required|integer',
            'weight' => 'required|integer',
            'description' => 'string'
        ]);

        $noteCriteria = NoteCriteria::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '_' .time(),
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
            'max_rate' => 'integer',
            'min_rate' => 'integer',
            'weight' => 'integer',
            'description' => 'string'
        ]);

        $noteCriteria = NoteCriteria::find($id);

        if($noteCriteria == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("NOT_FOUND_NOTE_CRITERIA_ID");
            $notFoundError->setMessage("note criteria with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $noteCriteria->update(
            $request->only([
                'name',
                'max_rate',
                'min_rate',
                'weight',
                'description'
            ])
        );

        return response()->json($noteCriteria, $this->successStatus);

    }


}

