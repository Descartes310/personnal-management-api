<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assignment;
use App\AssignmentType;
use App\APIError;

class AssignmentController extends Controller
{
    protected $succesStatus = 200;
    protected $notFoundStatus = 404;
    protected $badRequest = 200;

    /**
     * delete an assignement
     * @author Brell Sanwouo
     */

    public function delete ($id){
        $assignment = Assignment::find($id);
        if(!$assignment){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("ASSIGNMENT_NOT_FOUND");
            $unauthorized->setMessage("Assignment id not found in database.");

            return response()->json($unauthorized, 404);
        }

        return response()->json(null);
    }

    /**
     * find a spacific assignement 
     * @author Brell Sanwouo
     */
    public function find($id){
        $assignment = Assignment::find($id);
        if($assignment == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("ASSIGNMENT_NOT_FOUND");
            $unauthorized->setMessage("Assignment id not found in database.");

            return response()->json($unauthorized, 404);
        }
        return response()->json($assignment);
    }

    /**
     * get all assignements with spécific value 
     * @author Brell Sanwouo
     */

    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s;
        $page = $request->page;
        $assignment = Assignment::where('raison', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);

        return response()->json($assignment);
    }

     /**
     * create and update of a assignment
     * @author daveChimba
     */
    public function create(Request $request){

        $request->validate([
            'user_id' => 'required',
            'assignment_type_id' => 'required',
            'destination' => 'required',
            'signature_date' => 'required',
            'installation_date' => 'required',
            'raison' => 'required'
        ]);

        $user = User::find($request->user_id);

        if($user == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("USER_NOT_FOUND");
            $assignmentError->setMessage("No user found with id $user_id");

            return response()->json($assignmentError, 404);
        }

        $assignmentType = AssignmentType::find($request->assignment_type_id);

        if($assignmentType == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("ASSIGNMENT_TYPE_NOT_FOUND");
            $assignmentError->setMessage("No assignment type found with id $assignment_type_id");

            return response()->json($assignmentError, 404);
        }

        $assignment = Assignment::create([
            'user_id' => $request->user_id,
            'assignment_type_id' => $request->assignment_type_id,
            'destination' => $request->destination,
            'signature_date' => $request->signature_date,
            'installation_date' => $request->installation_date,
            'raison' => $request->raison
        ]);

        return response()->json($assignment, 201);

    }

    public function update(Request $request, $id){

        $request->validate([
            'user_id' => 'required',
            'assignment_type_id' => 'required',
            'destination' => 'required',
            'signature_date' => 'required',
            'installation_date' => 'required',
            'raison' => 'required'
        ]);

        $assignment = Assignment::find($id);

        if($assignment == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("ASSIGNMENT_NOT_FOUND_ID");
            $assignmentError->setMessage("No assignment found with id $assignment");
            return response()->json($assignmentError, 404);
        }

        $user = User::find($request->user_id);

        if($user == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("USER_NOT_FOUND");
            $assignmentError->setMessage("No user found with id $user_id");

            return response()->json($assignmentError, 404);
        }

        $assignmentType = AssignmentType::find($request->assignment_type_id);

        if($assignmentType == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("ASSIGNMENT_TYPE_NOT_FOUND");
            $assignmentError->setMessage("No assignment type found with id $assignmentType");

            return response()->json($assignmentError, 404);
        }

        // $data = $request->all();
        $assignment->update([
            'user_id' => $request->user_id,
            'assignment_type_id' => $request->assignment_type_id,
            'destination' => $request->destination,
            'signature_date' => $request->signature_date,
            'installation_date' => $request->installation_date,
            'raison' => $request->raison
        ]);

        return response()->json($assignment, 200);

    }






}
