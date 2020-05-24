<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\APIError;


class SettingController extends Controller
{

    public function create (Request $request){
        $this->validate($request->all(), [
            'key' => 'required | unique:settings'
        ]);

        $data = $request->only([
            'key',
            'value',
            'description'
        ]);

        $setting = Setting::create($data);
        return response()->json($setting);
    }


    public function delete($id){
        $setting = Setting::find($id);
        if($setting == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SETTING_NOT_FOUND");
            $unauthorized->setMessage("setting id not found");

            return response()->json($unauthorized, 404);
        }
        $setting->delete($setting);
        return response(null);
    }


    public function get(Request $req){
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $settings = Setting::where('subject', 'LIKE', '%' . $s . '%')->orWhere('message', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $settings = Setting::where('subject', 'LIKE', '%' . $s . '%')->orWhere('message', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $settings = Setting::paginate($limit);
            } else {
                $settings = Setting::all();
            }
        }

        return response()->json($settings);
    }

    public function find($id){
        $setting = Setting::find($id);
        if($setting == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SETTINGS_NOT_FOUND");
            $unauthorized->setMessage("settings id not found");

            return response()->json($unauthorized, 404);
        }
        return response()->json($setting);
    }

    public function update(Request $request, $id){
        $request->validate([
            'key' => 'required'
        ]);

        $data = $request->only([
            'key',
            'value',
            'description'
        ]);
        
        $setting = Setting::find($id);
        if($setting == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SETTING_NOT_FOUND");
            $unauthorized->setMessage("setting id not found");

            return response()->json($unauthorized, 404);
        }

        if($request->key != $setting->key){
            $setting_already_existing = Setting::whereKey($request->key);
            if($request->key == $setting_already_existing){
                $unauthorized = new APIError;
                $unauthorized->setStatus("400");
                $unauthorized->setCode("SETTING_ALREADY_IN_db");
                $unauthorized->setMessage("setting already existing");
    
                return response()->json($unauthorized, 404);
    
            }
        }
        $setting->update($data);
        return response()->json($setting);
    }
}

