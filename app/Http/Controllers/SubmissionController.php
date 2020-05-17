<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;

class SubmissionController extends Controller
{
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
