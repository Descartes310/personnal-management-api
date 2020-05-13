<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\User;
use App\UserProfile;
use App\APIError;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserInfo($id) {
        $user = User::whereId($id)->first();
        if(!$user){
            $response = new APIError;
            $response->setStatus("404");
            $response->setCode("USER_NOT_FOUND");
            $response->setMessage("The user with id $id was not found");
            return response()->json($response, 404);
        }
        $user_infos = UserProfile::whereUserId($id)->with('profile')->get();
        foreach ($user_infos as $user_info) {
            if($user_info->profile->type == 'file')
                $user[$user_info->profile->name] = url($user_info->value);
            else
                $user[$user_info->profile->name] = $user_info->value;
        }
        return response()->json($user);
    }
}
