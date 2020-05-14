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
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("TRAINING_NOT_FOUND");
            $notFound->setMessage("training id not found");

            return response()->json($notFound, 404);
        }
        return response()->json($training);
    }

    /**
     * get all trainings with specific parameters
     * @author aubin soh
     */
    public function get(Request $request) {

        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $trainings = Training::where('name', 'LIKE', '%' . $s . '%')
                    ->orWhere('description', 'LIKE', '%' . $s . '%')
                    ->paginate($limit);
            } else {
                $trainings = Training::where('name', 'LIKE', '%' . $s . '%')
                    ->orWhere('description', 'LIKE', '%' . $s . '%')
                    ->get();
            }
        } else {
            if ($limit || $page) {
                $trainings = Training::paginate($limit);
            } else {
                $trainings = Training::all();
            }
        }

        return response()->json($trainings);
      }

       /**
     * delete one training with id
     * @author aubin soh
     */
    public function delete($id){
        $training = Training::find($id);
        if($training == null) {
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("TRAINING_NOT_FOUND");
            $notFound->setMessage("training id not found");

            return response()->json($notFound, 404);
        }
        $training->delete();
        return response(null);
    }


}
