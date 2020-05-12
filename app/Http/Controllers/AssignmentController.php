<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Assignment;
use App\AssignmentType;
use App\User;

class AssignmentController extends Controller
{

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
            $assignmentError->setCode("NOT_FOUND_ID");
            $assignmentError->setMessage("No user found with id $request->user_id");

            return response()->json($assignmentError, 404);
        }

        $assignmentType = AssignmentType::find($request->assignment_type_id);

        if($assignmentType == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("NOT_FOUND_ID");
            $assignmentError->setMessage("No assignment type found with id $request->assignment_type_id");

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
            $assignmentError->setCode("NOT_FOUND_ID");
            $assignmentError->setMessage("No assignment found with id $id");

            return response()->json($assignmentError, 404);
        }

        $user = User::find($request->user_id);

        if($user == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("NOT_FOUND_ID");
            $assignmentError->setMessage("No user found with id $request->user_id");

            return response()->json($assignmentError, 404);
        }

        $assignmentType = AssignmentType::find($request->assignment_type_id);

        if($assignmentType == null) {
            $assignmentError = new APIError;
            $assignmentError->setStatus("404");
            $assignmentError->setCode("NOT_FOUND_ID");
            $assignmentError->setMessage("No assignment type found with id $request->assignment_type_id");

            return response()->json($assignmentError, 404);
        }

        // $data = $request->all();
        $assignment->update($request->all());

        return response()->json($assignment, 200);

    }






}
