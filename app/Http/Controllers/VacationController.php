<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vacation;
use App\Http\Controllers\Controller;
use App\VacationType;
use App\User;
use App\APIError;


class VacationController extends Controller
{
    public function find($id){
        $vacation = Vacation::with('vacationType')->whereId($id)->first();
        $vacation->user = User::findWithProfile($vacation->user_id);
        abort_if($vacation == null, 404, "vacation not found.");
        return response()->json($vacation);
	}


	public function get(Request $request) {
        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $vacations = Vacation::with('vacationType')->where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                $vacations = Vacation::with('vacationType')->where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                $vacations = Vacation::with('vacationType')->paginate($limit);
            } else {
                $vacations = Vacation::with('vacationType')->get();
            }
        }

        foreach ($vacations as $vacation) {
            $vacation->user = User::findWithProfile($vacation->user_id);
        }

        return response()->json($vacations);
    }


	public function delete($id){
		$vacat = Vacation::find($id);
        abort_if($vacat == null, 404, "vacation not found.");
        $vacat->delete($vacat);
        return response()->json([]);
    }


    public function create (Request $request){
        $request->validate([
            'user_id' => 'required',
            'vacation_type_id' => 'required',
            'requested_start_date' => 'required|date',
            'requested_days' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'status' => 'in:PENDING,APPROVED,REJECTED,CANCELLED'
        ]);

        $data = $request->only([
            'user_id',
            'vacation_type_id',
            'raison',
            'description',
            'requested_start_date',
            'accorded_start_date',
            'requested_days',
            'accorded_days',
            'file',
            'is_active',
            'status'
        ]);

        if(User::find($request->user_id) == null)
        {//user not existing in table vacation
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_ID_NOT_FOUND");
            $apiError->setErrors(['user_id' => 'user_id not existing']);

            return response()->json($apiError, 400);
        }

        if(VacationType::find($request->vacation_type_id) == null)
        {//vacation type not existing in table vacation
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("VACATION_TYPE_ID_NOT_FOUND");
            $apiError->setErrors(['vacation_type_id' => 'vacation_type_id not existing']);

            return response()->json($apiError, 400);
        }

        if($data['requested_days'] <= 0)
        {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("REQUESTED_DAYS_ERROR");
            $apiError->setErrors(['requested_days' => 'requested_days should be an entire and positive number']);

            return response()->json($apiError, 400);
        }


        if(isset($request->accorded_days))
        {
            $accorded_days = $request->accorded_days;
            if($accorded_days <= 0)
            {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("ACCORDED_DAYS_ERROR");
                $apiError->setErrors(['accorded_days' => 'accorded_days should be an entire and positive number']);

                return response()->json($apiError, 400);
            }
        }


        if(isset($request->file))
        {
            $file = $request->file('file');
            $path = null;
            if($file != null)
            {
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/document";
                $destinationPath = public_path($relativeDestination);
                $safeName = "document".time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                $path = "$relativeDestination/$safeName";
            }

        }
        $data['file'] = $path;

        $vacation = Vacation::create($data);
        return response()->json($vacation);
    }



    public function update(Request $request, $id){
        $vacation = Vacation::find($id);
        if($vacation == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATION_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'vacation id not existing']);

            return response()->json($apiError, 404);
        }

        $request->validate([
            'user_id' => 'required',
            'vacation_type_id' => 'required',
            'requested_start_date' => 'required|date',
            'requested_days' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'status' => 'in:PENDING,APPROVED,REJECTED,CANCELLED'
        ]);

        $data = $request->only([
            'user_id',
            'vacation_type_id',
            'raison',
            'description',
            'requested_start_date',
            'accorded_start_date',
            'requested_days',
            'accorded_days',
            'file',
            'is_active',
            'status'
        ]);

        if(isset($request->user_id))
        {
            if(User::find($request->user_id) == null)
            {//user not existing in table vacation
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        if(isset($request->vacation_type_id))
        {
            if(VacationType::find($request->vacation_type_id) == null)
            {//vacation type not existing in table vacation
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("VACATION_TYPE_ID_NOT_FOUND");
                $apiError->setErrors(['vacation_type_id' => 'vacation_type_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        if($data['requested_days'] < 0)
        {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("REQUESTED_DAYS_ERROR");
            $apiError->setErrors(['requested_days' => 'requested_days should be an entire and positive number']);

            return response()->json($apiError, 400);
        }


        if(isset($request->accorded_days))
        {
            $accorded_days = $request->accorded_days;
            if($accorded_days < 0)
            {
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("ACCORDED_DAYS_ERROR");
                $apiError->setErrors(['accorded_days' => 'accorded_days should be an entire and positive number']);

                return response()->json($apiError, 400);
            }
        }

        if(isset($request->file))
        {
            $file = $request->file('file');
            $path = null;

            if($file != null){
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/document";
                $destinationPath = public_path($relativeDestination);
                $safeName = "document".time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                $path = "$relativeDestination/$safeName";
                //Delete old vacation document if exists
                if ($vacation->file) {
                    $oldImagePath = public_path($vacation->file);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            }
            $data['file'] = $path;
        }

        $vacation->update($data);
        return response()->json($vacation);
    }

    //recuperation de toutes les vacation en cours
    function findByStatus($status){

        $vacation = Vacation::whereStatus($status)->count('*');
        if($vacation){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("VACATION_STATUS_NOT_FOUND");
            $apiError->setErrors(['user_id' => 'user_id not existing']);

        }

        return response()->json($vacation);
    }
}
