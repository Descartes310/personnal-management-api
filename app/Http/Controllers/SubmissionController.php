<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use App\APIError;

class SubmissionController extends Controller
{
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

    public function get(Request $request){
      $limit = $request->limit;
      $s = $request->s; 
      $page = $request->page; 
      $submissions = Submission::where('subject','LIKE','%'.$s.'%')
                                     ->paginate($limit); 
      return response()->json($submissions);
    }

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
