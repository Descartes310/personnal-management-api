<?php

namespace App\Http\Controllers;

use App\APIError;
use Illuminate\Http\Request;
use App\License;
use App\LicenseType;
use App\User;
use App\UserProfile;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class LicenseController extends Controller {


    public function get(Request $request) {


        $s = $request->s;
        $page = $request->page;
        $limit = null;
        $datas = [];
        $licenses = [];

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $licenses = License::where('raison', 'like', "%$s%")->with('license_type')->orWhere('description', 'like', "%$s%")->paginate($limit);
            } else {
                $licenses = License::where('raison', 'like', "%$s%")->with('license_type')->orWhere('description', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                $licenses = License::with('license_type')->paginate($limit);
            } else {
                $licenses = License::with('license_type')->get();
            }
        }

        foreach ($licenses as $key => $license) {
            $user = User::whereId($license->user_id)->first();
            $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
            foreach ($user_infos as $user_info) {
                if($user_info->profile->type == 'file')
                    $user[$user_info->profile->name] = url($user_info->value);
                else
                    $user[$user_info->profile->name] = $user_info->value;
            }
            $license['user'] = $user;
            array_push($datas, $license);
        }
        return response()->json($datas);
    }



    public function find($id){


        $license1 = License::find($id);
        $user = User::whereId($license1->user_id)->first();
        $license_type = LicenseType:: whereId($license1->license_type_id)->first();
        $license1['user_id'] = $user;
        $license1['license_type_id'] = $license_type;
        $license = $license1;
       
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

        if(isset($request->user_id)){
            if(User::find($request->user_id) == null){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("USER_ID_NOT_FOUND");
                $apiError->setErrors(['user_id' => 'user_id not existing '.$request->user_id]);
 
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
