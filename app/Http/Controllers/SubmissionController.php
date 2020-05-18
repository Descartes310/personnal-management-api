<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use App\APIError;

class SubmissionController extends Controller
{
    /**
     * find one submission with id
     * @author adamu aliyu
     */
    public function find($id){
        $submission = Submission::find($id);
        if($submission == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SUBMISSION_NOT_FOUND");
            $unauthorized->setMessage("submissions id not found");

            return response()->json($unauthorized, 404);
        }
        return response()->json($submission);
    }

    /**
     * get all  submissions with specific parameters
     * @author adamu aliyu
     */
    public function get(Request $req){
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $submissions = Submission::where('subject', 'LIKE', '%' . $s . '%')->orWhere('message', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $submissions = Submission::where('subject', 'LIKE', '%' . $s . '%')->orWhere('message', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $submissions = Submission::paginate($limit);
            } else {
                $submissions = Submission::all();
            }
        }

        return response()->json($submissions);
    }

    /**
     * delete one  submission with id
     * @author adamu aliyu
     */
    public function delete($id){
        $submission = Submission::find($id);
        if($submission == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SUBMISSION_NOT_FOUND");
            $unauthorized->setMessage("submissions id not found");

            return response()->json($unauthorized, 404);
        }
        $submission->delete($submission);
        return response(null);
    }

}
