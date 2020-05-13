<?php

namespace App\Http\Controllers;

use App\APIError;
use App\NoteCriteria;
use Illuminate\Http\Request;

class NoteCriteriaController extends Controller
{
    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    protected $badRequestStatus = 400;
     /**
     * Find an existing  NoteCriteria
     * *Author Warren TABA
     */
    
    public function find($id){
        $notecriteria = NoteCriteria::find($id);
        if($notecriteria == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("NOTCRITERIA_NOT_EXIST");
            $notexist->setMessage("NOTECRITERIA does not exist with id $id.");
            
            return response()->json($notexist,404);
        }
        return response()->json($notecriteria);
    }

    /**
     * Get All the NoteCriteria
     * *Author Warren TABA
     */

    public function get(Request $req){
        $limit = $req->limit;
        $s = $req->s;
        $notecriteria = NoteCriteria::where('name','LIKE','%'.$s.'%')->paginate($limit);
        if($notecriteria==null){
           $error_isempty = new APIError;
           $error_isempty->setStatus("404");
           $error_isempty->setCode("NOTECRITERIA_IS_EMPTY");
           $error_isempty->setMessage("NOTECRITERIA is empty in Database.");
           
           return response()->json($error_isempty,404);
        }
        return response()->json($notecriteria);
    }
    
     /**
      * Delete the choosen NoteCriteria 
      *Author Warren TABA
      */

      public function delete($id){
        $notecriteria = NoteCriteria:: find($id);
        if($notecriteria == null){
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("NOTECRITERIA_NOT_EXIST");
            $notexist->setMessage("NOTECRITERIA id not found");

            return response()->json($notexist, 404);
        }
        $notecriteria->delete($notecriteria);
        return response()->json($notecriteria);
      }

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

