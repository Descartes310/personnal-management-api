<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\User;
use App\Profile;
use App\ProfileUpdate;

class ProfileUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            'user_id' => 'required|numeric',
            'profile_id' => 'required|numeric',
        ]);

        //We find the user
        $user = User::find($request->user_id);
        if ($user == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! No user found");
            return response()->json($apiError, 400);
        }

        //we find the profile
        $profile = Profile::find($request->profile_id);
        if ($profile == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("PROFILE_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! No profile found");
            return response()->json($apiError, 400);
        }

        // les données de la requête sont valides
        $profileupdate = ProfileUpdate::create([
            'user_id' => $request->user_id,
            'profile_id' => $request->profile_id,
            'old_value' => $request->old_value,
            'new_value' => $request->new_value,
            'is_accepted' => $request->is_accepted,
            'raison' => $request->raison,
        ]);
        return response()->json($profileupdate, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request->all(), [
            'user_id' => 'required|numeric|exists:App\User,id',
            'profile_id' => 'required|numeric|exists:App\Profile,id',
        ]);

        $profileupdate = ProfileUpdate::find($id);

        $datas = $request->only([
            'user_id',
            'profile_id',
            'old_value',
            'new_value',
            'is_accepted',
            'raison',
        ]);

        if ($profileupdate == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("PROFILE_UPDATE_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! This profileUpdate doesn't exist");
            return response()->json($apiError, 400);
        }
        // les données de la requête sont valides
        $profileupdate->update($datas);
        return response()->json($profileupdate, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
