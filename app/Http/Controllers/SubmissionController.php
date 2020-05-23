<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use App\APIError;
use App\User;
use App\UserProfile;

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
        $data = [];

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
        foreach ($submissions as $key => $submission) {
            $user = User::whereId($submission->user_id)->first();
            $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
            foreach ($user_infos as $user_info) {
                if($user_info->profile->type == 'file')
                    $user[$user_info->profile->slug] = url($user_info->value);
                else
                    $user[$user_info->profile->slug] = $user_info->value;
            }
            $submission['sender'] = $user;
            $user = User::whereId($submission->dest_user_id)->first();
            $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
            foreach ($user_infos as $user_info) {
                if($user_info->profile->type == 'file')
                    $user[$user_info->profile->slug] = url($user_info->value);
                else
                    $user[$user_info->profile->slug] = $user_info->value;
            }
            $submission['receiver'] = $user;
            array_push($data, $submission);
        }

        return response()->json($data);
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



    public function create(Request $request){
        $request->validate([
            'user_id' => 'required',
            'dest_user_id' => 'nullable',
            'message' => 'required'
        ]);

        $filePaths = $this->uploadMultipleFiles($request, 'files', 'submissions', ['file', 'mimes:pdf,doc,ppt,xls,rtf']);
        $data['files'] = json_encode($filePaths);
        $data = array_merge($data, $request->only(['user_id', 'dest_user_id', 'subject', 'message']));
        $submission = Submission::create($data);
        $submission->files = json_decode($submission->files);
        return response()->json($submission);
    }

    public function update(Request $request, $id){

        $request->validate([
            'user_id' => 'required',
            'dest_user_id' => 'nullable',
            'message' => 'required'
        ]);

        $submission = Submission::find($id);
        abort_if($submission == null, 404, "No submission found with id $id");

        if ($request->has('files')) {
            $filePaths = $this->uploadMultipleFiles($request, 'files', 'submissions', ['file', 'mimes:pdf,doc,ppt,xls,rtf']);
            $data['files'] = json_encode($filePaths);
        }

        $data = array_merge($data, $request->only(['user_id', 'dest_user_id', 'subject', 'message']));
        $submission->update($data);
        $submission->files = json_decode($submission->files);
        return response()->json($submission);
    }
}
