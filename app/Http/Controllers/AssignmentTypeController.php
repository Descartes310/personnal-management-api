<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\APIError;
use App\AssignmentType;

class AssignmentTypeController extends Controller {

    protected $successStatus = 200;
    protected $createStatus = 201;
    protected $notFoundStatus = 404;
    protected $badRequestStatus = 400;
    
    /**
     * Create an assignment type with name, slug and description
     * @author Arléon Zemtsop
     * @email arleonzemtsop@gmail.com
     */
    public function create(Request $request) {

        $this->validate($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:assignment_types',
            'description' => 'required|string'
        ]);

        $assignmentType = AssignmentType::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description
        ]);

        return response()->json($assignmentType, $this->createStatus);

    }

    /**
     * Update an assignment with name, slug and description
     * @author Arléon Zemtsop
     * @email arleonzemtsop@gmail.com
     */
    public function update(Request $request, $id) {

        $this->validate($request->all(), [
            'name' => 'string',
            'slug' => 'string',
            'description' => 'string'
        ]);

        $assignmentType = AssignmentType::find($id);

        if($assignmentType == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("NOT_FOUND_ASSIGNMENT_ID");
            $notFoundError->setMessage("Assignment type with id " . $id . " not found");

            return response()->json($notFoundError, $this->notFoundStatus);
        }

        $slug = $request->slug;

        $foundAssignmentType = AssignmentType::whereSlug($slug)->first();
        
		if($foundAssignmentType != null && $foundAssignmentType != $assignmentType) {
			$badRequestError = new APIError;
            $badRequestError->setStatus("400");
            $badRequestError->setCode("ASSIGNMENT_SLUG_ALREADY_EXIST");
            $badRequestError->setMessage("Assignment type with slug " . $slug . " already exist");

            return response()->json($badRequestError, $this->badRequestStatus);
		}

        $assignmentType->update(
            $request->only([ 
                'name', 
                'slug', 
                'description'
            ])
        );

        return response()->json($assignmentType, $this->successStatus);

    }
}
