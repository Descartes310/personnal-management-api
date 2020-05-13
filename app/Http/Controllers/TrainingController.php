<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Training;

class TrainingController extends Controller
{
    /**
     * find one training with id
     * @author aubin soh
     */

    public function find($id){
        $training = Training::find($id);
        if($training == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("TRAINING_NOT_FOUND");
            $unauthorized->setMessage("training id not found");

            return response()->json($unauthorized, 404); 
        }
        return response()->json($training);
    }

    /**
     * get all trainings with specific parameters
     * @author aubin soh
     */
    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s; 
        $page = $request->page; 
        $trainings = Training::where('name','LIKE','%'.$s.'%')
                                       ->paginate($limit); 
        return response()->json($trainings);
      }

       /**
     * delete one training with id
     * @author aubin soh
     */
    public function delete($id){
        $training = Training::find($id);
        if($training == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("TRAINING_NOT_FOUND");
            $unauthorized->setMessage("training id not found");

            return response()->json($unauthorized, 404); 
        }
        $training->delete($training);
        return response(null);
    }


}
