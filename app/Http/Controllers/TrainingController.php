<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\APIError;
use App\Training;

class TrainingController extends Controller
{
    protected $successStatus = 200;
    protected $createStatus = 201;
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
     * Creation of a training
     * @author warren taba
     * @email fotiewarren50@gmail.com
     */
    public function create(Request $request) {

        $this->validate($request->all(), [
            'name' => 'required|string',
            'trainer' =>'required|string',
            'description' => 'string',
            'start_date' =>'date',
            'duration' => 'required|integer|min:1|max:100',
            'location' => 'string'
        ]);
        
        $training = Training::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '_' .time(),
            'trainer' =>$request->trainer,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
            'location' =>$request->location,
            'is_online' =>$request->is_online
        ]);

        return response()->json($training, $this->createStatus);

    }

    /**
     * Update of Traininng
     * @author warren taba
     * @email fotiewarren50@gmail.com
     */

    public function update(Request $request, $id) {

        $this->validate($request->all(), [
            'name' => 'string',
            'trainer' => 'string',
            'description' => 'string',
            'start_date' => 'string',
            'duration' => 'string',
            'location' => 'string',
            'is_online' => 'Boolean',
        ]);

        $training = Training::find($id);

        if($training == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("NOT_FOUND_TRAINING_ID");
            $notFoundError->setMessage("Training with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $training->update(
            $request->only([
                'name',
                'trainer',
                'description',
                'start_date',
                'duration',
                'location'
            ])
        );

        return response()->json($training, $this->successStatus);

    }


}
