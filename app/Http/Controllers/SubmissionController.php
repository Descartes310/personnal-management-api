<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'user_id' => 'required',
            'dest_user_id' => 'required',
            'message' => 'required'
        ]);


        if ($file = $request->file('files')) {
            $request->validate(['files' => 'file|mimes:pdf,doc,ppt,xls,rtf']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/submissions";
            $destinationPath = public_path($relativeDestination);
            do{
                $safeName = Str::random(5) . time() . '.' . $extension;
            }while(file_exists($destinationPath . '/' . $safeName));

            $file->move($destinationPath, $safeName);
            $data['files'] = url("$relativeDestination/$safeName");
        }

        $data = $request->only(['user_id', 'dest_user_id', 'subject', 'message', 'files']);
        $submission = Submission::create($data);
        return response()->json($submission, 200);
    }

    public function update(Request $request, $id){

        $request->validate([
            'user_id' => 'required',
            'message' => 'required'
        ]);
        $submission = Submission::find($id);
        if($submission == null) {
            abort(404, "No submission found with id $id");
        }
        if ($file = $request->file('files')) {
            $request->validate(['files' => 'file|mimes:pdf,doc,ppt,xls,rtf']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/submissions";
            $destinationPath = public_path($relativeDestination);
            do{
                $safeName = Str::random(5) . time() . '.' . $extension;
            }while(file_exists($destinationPath . '/' . $safeName));

            $file->move($destinationPath, $safeName);
            $data['files'] = url("$relativeDestination/$safeName");
        }

        $data = $request->only(['user_id', 'dest_user_id', 'subject', 'message', 'files']);
        $submission->update($data);
        return response()->json($data, 200);
    }
}
