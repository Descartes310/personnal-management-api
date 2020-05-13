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



    /**
     * Find an existing  AssignmentType
     * @author Warren TABA
     * @email fotiewarren50@gmail.com
     */
    public function find($id)
    {
        $assign_type = AssignmentType::find($id);
        if ($assign_type == null) {
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("ASSIGNMENT_TYPE_NOT_EXIST");
            $notexist->setMessage("No AssignmentType with id $id.");

            return response()->json($notexist, 404);
        }
        return response()->json($assign_type);
    }


    /**
     * Get All the AssignmentType
     * @author Warren TABA
     * @email fotiewarren50@gmail.com
     */
    public function get(Request $req)
    {
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $assignTypes = AssignmentType::where('name', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $assignTypes = AssignmentType::where('name', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $assignTypes = AssignmentType::paginate($limit);
            } else {
                $assignTypes = AssignmentType::all();
            }
        }

        return response()->json($assignTypes);
    }

    /**
     * Delete the choosen AssignmentType
     * @author Warren TABA
     * @email fotiewarren50@gmail.com
     */
    public function delete($id)
    {
        $assign_type = AssignmentType::find($id);
        if ($assign_type == null) {
            $notexist = new APIError;
            $notexist->setStatus("404");
            $notexist->setCode("ASSIGNMENT_TYPE_NOT_EXIST");
            $notexist->setMessage("AssignmentType id not found");

            return response()->json($notexist, 404);
        }
        $assign_type->delete();
        return response(null);
    }
}
