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

    /**
     * create of a training
     * @author daveChimba
     */

    public function create(Request $request){

        $this->validate($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'trainer' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'location' => 'required'
        ]);

        $training = Training::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'trainer' => $request->trainer,
            'description' => $request->description,
            'duration' => $request->duration,
            'location' => $request->location
        ]);

        return response()->json($training, 201);

    }

    /**
     * update of a training
     * @author daveChimba
     */


    
    public function update(Request $request, $id){

       
        $this->validate($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'trainer' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'location' => 'required'
        ]);

        $training = Training::find($id);
        if($training == null) {
            $trainingError = new APIError;
            $trainingError->setStatus("404");
            $trainingError->setCode("TRAINING_NOT_FOUND");
            $trainingError->setMessage("No training found with id $request->user_id");

            return response()->json($trainingError, 404);
        }

        $training->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'trainer' => $request->trainer,
            'description' => $request->description,
            'duration' => $request->duration,
            'location' => $request->location,
        ]);

        return response()->json($training, 200);

    }


}
