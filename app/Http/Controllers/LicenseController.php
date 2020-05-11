<?php

namespace App\Http\Controllers;

use DB;
use App\APIError;
use Illuminate\Http\Request;
use App\License;
use App\Http\Controllers\Controller;


class LicenseController extends Controller{


    public function get(Request $request){
        
        
        $s=$request['s'];
        $limit=$request->limit;
        //return $request->all();

        if ($s) {
            if($limit){
                return License::where('raison', 'like', $s)->paginate($limit);
            }

            if($limit){
                return License::where('raison', 'like', $s)->paginate($limit);
            }

            return License::where('raison', 'like', $s);
        }

        if ($limit) {
            return License::paginate($limit);
        }
        if ($page) {
           return License::paginate(15);
        }
        return License::all();

    }



    public function find($id){
        $license = License::find($id);

        if ($license == null) {
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("NOT FOUND");
            $notFound->setMessage("licnse not founded.");

            return response()->json($notFound, 404);
        }

        return response()->json($license);
    }


    public function archive($id){
        $license = License::find($id);

        if ($license == null) {
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("NOT FOUND");
            $notFound->setMessage("licnse not founded.");

            return response()->json($notFound, 404);
        }

        $update=[
            'user_id'=>$license->user_id,
            'license_type_id'=>$license->license_type_id,
            'raison'=>$license->raison,
            'description'=>$license->description,
            'requested_start_date'=>$license->requested_start_date,
            'accorded_start_date'=>$license->accorded_start_date,
            'requested_days'=>$license->requested_days,
            'accorded_days'=>$license->accorded_days,
            'is_active'=>false,
            'file'=>$license->file,
            'status'=>$license->status,
            'created_at'=>$license->created_at
        ];

        $license->update($update);
        return $license;
    }


    public function delete($id){
        $license = License::find($id);
        if ($license == null) {
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("NOT FOUND");
            $notFound->setMessage("licnse not founded.");

            return response()->json($notFound, 404);
        }

        $license->delete($license);
        return response()->json('status=200', 200);
    }
}
