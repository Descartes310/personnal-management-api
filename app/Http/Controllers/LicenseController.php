<?php

namespace App\Http\Controllers;

use App\APIError;
use Illuminate\Http\Request;
use App\License;
use App\LicenseType;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class LicenseController extends Controller {


    public function get(Request $request) {


        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                return License::where('raison', 'like', "%$s%")->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return License::paginate($limit);
            } else {
                return License::all();
            }
        }
    }



    public function find($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not found.");
        return response()->json($license);
    }


    public function changeStatus($id){

        $license = License::find($id)->first();
        abort_if($license == null, 404, "license not found.");
        $license->update(['is_active' => !$license->is_active]);
        return $license;
    }


    public function delete($id){

        $license = License::find($id);
        abort_if($license == null, 404, "license not found.");
        $license->delete();
        return response()->json([]);
    }


     public function create (Request $request){
        $now = Carbon::now();
        $request->validate([
           'user_id' => 'required',
           'license_type_id' => 'required',
           'requested_start_date' => 'required|date|after:'.$now,
           'requested_days' => 'required|numeric|min:0',
           'is_active' => 'required|boolean',
           'status' => 'in:PENDING,APPROVED,REJECTED,CANCELLED'
        ]);
        $data = $request->only([
          'user_id',
          'license_type_id',
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
         if(User::find($request->user_id) == null){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_ID_NOT_FOUND");
            $apiError->setErrors(['user_id' => 'user_id not existing']);

            return response()->json($apiError, 400);
        }

         if(LicenseType::find($request->license_type_id) == null){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("LICENSE_TYPE_ID_NOT_FOUND");
            $apiError->setErrors(['license_type_id' => 'license_type_id not existing']);

            return response()->json($apiError, 400);
        }

        if($data['requested_days'] <= 0){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("REQUESTED_DAYS_ERROR");
            $apiError->setErrors(['requested_days' => 'requested_days should be an entire and positive number']);

            return response()->json($apiError, 400);
        }


        if(isset($request->accorded_days)){
            $accorded_days = $request->accorded_days;
            if($accorded_days <= 0){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("ACCORDED_DAYS_ERROR");
                $apiError->setErrors(['accorded_days' => 'accorded_days should be an entire and positive number']);

                return response()->json($apiError, 400);
            }
        }


        if(isset($request->file)){
            $file = $request->file('file');
            $path = null;
            if($file != null){
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/permissions";
                $destinationPath = public_path($relativeDestination);
                $safeName = "document".time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                $path = "$relativeDestination/$safeName";
            }
            $data['file'] = $path;
        }

        $license = License::create($data);
        return response()->json($license);
    }

    public function update(Request $request, $id){
        $license = License::find($id);
        if($license == null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("license_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'license id not existing']);

            return response()->json($apiError, 404);
        }

        $request->validate([
            'user_id' => 'required',
            'license_type_id' => 'required',
            'requested_start_date' => 'required|date',
            'requested_days' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'status' => 'in:PENDING,APPROVED,REJECTED,CANCELLED'
        ]);

        $data = $request->only([
            'user_id',
            'license_type_id',
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

        if(isset($request->user_id)){
            if(User::find($request->user_id) == null){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        if(isset($request->license_type_id)){
            if(LicenseType::find($request->license_type_id) == null){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("license_TYPE_ID_NOT_FOUND");
                $apiError->setErrors(['license_type_id' => 'license_type_id not existing']);

                return response()->json($apiError, 400);
            }
        }

        if($data['requested_days'] < 0){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("REQUESTED_DAYS_ERROR");
            $apiError->setErrors(['requested_days' => 'requested_days should be an entire and positive number']);

            return response()->json($apiError, 400);
        }


        if(isset($request->accorded_days)){
            $accorded_days = $request->accorded_days;
            if($accorded_days < 0){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("ACCORDED_DAYS_ERROR");
                $apiError->setErrors(['accorded_days' => 'accorded_days should be an entire and positive number']);

                return response()->json($apiError, 400);
            }
        }

        if(isset($request->file)){
            $file = $request->file('file');
            $path = null;

            if($file != null){
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/permissions";
                $destinationPath = public_path($relativeDestination);
                $safeName = "document".time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                $path = "$relativeDestination/$safeName";
                if ($license->file) {
                    $oldImagePath = public_path($license->file);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            }
            $data['file'] = $path;
        }

        $license->update($data);
        return response()->json($license);
    }
}
