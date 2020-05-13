<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Profile;
use App\UserProfile;
use App\SelectOption;
use App\User;

class UserController extends Controller
{
    public function create(Request $request) {
        $profiles = Profile::get();
        $rules = [
            'login' => ['required', 'alpha_num', 'unique:App\User'],
            'password' => ['required'],
        ];
        // Validation loop
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
       
        $user = User::create([
            'login' => $request->login,
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



}
