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
     * Create an assignement with id_assignment, user_id, assignment_type_id
     * destination, signature_date, installation_date, reason, description
     * @author Brell Sanwouo
     * @param Request $request
     */

    // public function create(Request $request){
    //     $this->validate($request->all(), [
    //         'user_id' => 'required|string',
    //         'assignment_type_id' => 'required|string',
    //         'signature_date' => 'required|date|date_format:Y-m-d',
    //         'installation_date' => 'required|date|date_format:Y-m-d',
    //         'raison' => 'required|string'
    //     ]);

    //     $assignmentType = AssignmentType::find($request->assignment_type_id);
        
    //     if( ! $assignmentType ){
    //         $unauthorized = new APIError;
    //         $unauthorized->setStatus("404");
    //         $unauthorized->setCode("ASSIGNMENT_NOT_FOUND_IN_DB");
    //         $unauthorized->setMessage("Assignment not found in your database");
    //         return response()->json($unauthorized, 404);
    //     }

    //     $assignment = Assignment::create([
    //         'user_id' => $request->user_id,
    //         'assignment_type_id' =>$request->assignment_type_id,
    //         'destination' => $request->destination,
    //         'signature_date' => $request->signature_date,
    //         'installation_date' => $request->installation_date,
    //         'raison' => $request->raison
    //     ]);
    //     return response()->json($assignment, $this->succesStatus);

    // }

    /**
     * delete an assignement
     * @author Brell Sanwouo
     */

    public function delete ($id){
        $assignment = Assignment::find($id);
        if(!$assignment){
            return response()->json("Assignment not found in bd", $this->notFoundStatus);
        }

        $assignment->delete();
        return response()->json($assignment, $this->succesStatus);
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

}

