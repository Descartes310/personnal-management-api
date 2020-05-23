<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Profile;
use App\UserProfile;
use App\SelectOption;
use App\User;
use Auth;
use App\APIError;
use App\ChatDiscussion;
use App\City;
use App\Career;
use App\ProSituation;

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
                $user[$user_info->profile->slug] = url($user_info->value);
            else
                $user[$user_info->profile->slug] = $user_info->value;
        }

        // The empty user field must be present in response, with null value
        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            if ( ! isset($user[$profile->slug]) ) {
                $user[$profile->slug] = null;
            }
        }

        $career = Career::whereUserId($id)->orderBy('updated_at', 'desc')->first();
        
        if($career) {
            $proSituation = ProSituation::find($career->pro_situation_id);
            $user['pro_situation'] = $proSituation->name;
        }

        $user->roles;
        $user['permissions'] = $user->allPermissions();

        return response()->json($user);
    }

    public function getUsers(Request $req) {
        $connected_user = Auth::user();

        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($limit || $page) {
            $users = User::paginate($limit);
        } else {
            $users = User::all();
        }

        foreach ($users as $key => $user) {
            $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
            foreach ($user_infos as $user_info) {
                if($user_info->profile->type == 'file')
                    $user[$user_info->profile->slug] = url($user_info->value);
                else
                    $user[$user_info->profile->slug] = $user_info->value;
            }
            $discussion = ChatDiscussion::whereUser1IdAndUser2Id($connected_user->id, $user->id)->first();
            if($discussion == null) {
                $discussion = ChatDiscussion::whereUser2IdAndUser1Id($connected_user->id, $user->id)->first();
                if($discussion != null) {
                    $user['chat_discussion_id'] = $discussion->id;
                    $user['chat_last_message'] = $discussion->last_message;
                    $user['chat_last_date'] = $discussion->updated_at;
                } else {
                    $user['chat_discussion_id'] = null;
                    $user['chat_last_message'] = null;
                    $user['chat_last_date'] = null;
                }
            } else {
                $user['chat_discussion_id'] = $discussion->id;
                $user['chat_last_message'] = $discussion->last_message;
                $user['chat_last_date'] = $discussion->updated_at;
            }

            // The empty user field must be present in response, with null value
            $profiles = Profile::all();
            foreach ($profiles as $profile) {
                if ( ! isset($user[$profile->slug]) ) {
                    $user[$profile->slug] = null;
                }
            }
            $users[$key] = $user;
        }

        return response()->json($users);
    }



    public function search(Request $req) {
        $queries = $req->except('limit', 'page');
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        $ids = [];
        foreach ($queries as $slug => $value) {
            $profileId = Profile::where('slug', $slug)->value('id');
            if ($profileId) {
                $tmpIds = UserProfile::where('profile_id', $profileId)->where('value', 'like', "%$value%")->pluck('user_id')->toArray();
                $ids = array_merge($ids, $tmpIds);
            }
        }

        if ($limit || $page) {
            $users = User::whereIn('id', $ids)->paginate($limit);
        } else {
            $users = User::whereIn('id', $ids)->get();
        }

        foreach ($users as $key => $user) {
            $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
            foreach ($user_infos as $user_info) {
                if ($user_info->profile->type == 'file')
                    $user[$user_info->profile->slug] = url($user_info->value);
                else
                    $user[$user_info->profile->slug] = $user_info->value;
            }

            // The empty user field must be present in response, with null value
            $profiles = Profile::all();
            foreach ($profiles as $profile) {
                if (!isset($user[$profile->slug])) {
                    $user[$profile->slug] = null;
                }
            }
        }

        return response()->json($users);
    }


    /**
     * Delete user
     */
    public function delete(User $user) {
        $user->delete(); //No need to delete user field in user profile because this is only a soft delete
    }

    /**
     * @author Armel Nya
     */
    public function create(Request $request) {
        $profiles = Profile::get();
        $rules = [
            'login' => ['required', 'alpha_num', 'unique:App\User'],
            'password' => ['required'],
            'city' => ['required'],
        ];
        // La boucle de validation
        foreach ($profiles as $profile) {
            $rule = [];
            if ($profile->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if ($profile->is_unique) {
                $rule[] = function ($attribute, $value, $fail) use ($profile) {
                    $count = UserProfile::where('profile_id', $profile->id)->where('value', $value)->count();
                    if ($count > 0) {
                        $fail($attribute . ' must be unique');
                    }
                };
            }

            if ($profile->min) {
                $rule[] = 'min:' . $profile->min;
            }

            if ($profile->max) {
                $rule[] = 'max:' . $profile->max;
            }

            if (strtolower($profile->type) == 'select') {
                $options = SelectOption::where('profile_id', $profile->id)->pluck('key');
                $rule[] = Rule::in($options);
            }

            if (strtolower($profile->type) == 'email') {
                $rule[] = 'email';
            }

            if (strtolower($profile->type) == 'file') {
                $rule[] = 'file';
            }

            if (strtolower($profile->type) == 'number') {
                $rule[] = 'numeric';
            }

            if (strtolower($profile->type) == 'date') {
                $rule[] = 'date';
            }

            if (strtolower($profile->type) == 'url') {
                $rule[] = 'url';
            }

            $rules[ $profile->slug ] = $rule;
        }

        $this->validate($request->all(), $rules);
        // si la validation est ok on cree le user
        $user = User::create([
            'login' => $request->login,
            'city' => $request->city,
            'password' => bcrypt($request->password)
        ]);

        // Insertion loop
        foreach ($profiles as $profile) {
            $value = null;
            if ($request->has($profile->slug)) {
                if (strtolower($profile->type) == 'file') {
                    if ($file = $request->file($profile->slug)) {
                        $extension = $file->getClientOriginalExtension();
                        $relativeDestination = "uploads/users";
                        $destinationPath = public_path($relativeDestination);
                        $safeName = Str::slug($user->login) . time() . '.' . $extension;
                        $file->move($destinationPath, $safeName);
                        $value = "$relativeDestination/$safeName";
                    }
                } else {
                    $value = $request[ $profile->slug ];
                }

                if ($value) {
                    UserProfile::create([
                        'user_id' => $user->id,
                        'profile_id' => $profile->id,
                        'value' => $value
                    ]);
                }
            }
            $user[ $profile->slug ] = $value;
        }

        

        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $result = User::find($id);

        if($user == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("USER_NOT_FOUND");
            $unauthorized->setMessage("No user found with id $id");
                return response()->json($unauthorized, 404);
        }
        $profiles = Profile::get();
        $rules = [
            'login' => ['alpha_num', Rule::unique('users')->ignore($id,'id')],
        ];
        // boucle de validation
        foreach ($profiles as $profile) {
            $rule = [];

            $rule[] = 'nullable';

            if ($profile->is_unique) {
                $rule[] = function ($attribute, $value, $fail) use ($profile, $user) {
                    $count = UserProfile::where('profile_id', $profile->id)
                                ->where('user_id', '<>', $user->id)
                                ->where('value', $value)
                                ->count();
                    if ($count > 0) {
                        $fail($attribute . ' must be unique');
                    }
                };
            }

            if ($profile->min) {
                $rule[] = 'min:' . $profile->min;
            }

            if ($profile->max) {
                $rule[] = 'max:' . $profile->max;
            }

            if (strtolower($profile->type) == 'select') {
                $options = SelectOption::where('profile_id', $profile->id)->pluck('key');
                $rule[] = Rule::in($options);
            }

            if (strtolower($profile->type) == 'email') {
                $rule[] = 'email';
            }

            if (strtolower($profile->type) == 'file') {
                $rule[] = 'file';
            }

            if (strtolower($profile->type) == 'number') {
                $rule[] = 'numeric';
            }

            if (strtolower($profile->type) == 'date') {
                $rule[] = 'date';
            }

            if (strtolower($profile->type) == 'url') {
                $rule[] = 'url';
            }

            $rules[ $profile->slug ] = $rule;
        }

        $this->validate($request->all(), $rules);

        // Insertion or update
        foreach ($profiles as $profile) {
            $userProfile = UserProfile::where('user_id', $user->id)->where('profile_id', $profile->id)->first();
            $value = (null != $userProfile) ? $userProfile->value : null;
            if ($request->has($profile->slug)) {
                if (strtolower($profile->type) == 'file') {
                    if ($file = $request->file($profile->slug)) {
                        $extension = $file->getClientOriginalExtension();
                        $relativeDestination = "uploads/users";
                        $destinationPath = public_path($relativeDestination);
                        $safeName = Str::slug($user->login) . time() . '.' . $extension;
                        $file->move($destinationPath, $safeName);
                        $value = "$relativeDestination/$safeName";
                    }
                } else {
                    $value = $request[ $profile->slug ];
                }

                if ($value) {
                    if ($userProfile) {
                        $userProfile->value = $value;
                        $userProfile->save();
                    } else {
                        UserProfile::create([
                            'user_id' => $user->id,
                            'profile_id' => $profile->id,
                            'value' => $value
                        ]);
                    }
                }
            } else {
                if ($userProfile) {
                    $userProfile->delete();
                }
                $value = null;
            }
            $result[ $profile->slug ] = $value;
        }

        if($request->city) {
            $user->city = $request->city;
        }
        $user->save();

        return response()->json($result);
    }

    public function getCities(){
        $cities = City::all();
        return response()->json($cities);
    }

    public function get(Request $request){

        $users = User::All();

        return response()->json($users);
    }
}
