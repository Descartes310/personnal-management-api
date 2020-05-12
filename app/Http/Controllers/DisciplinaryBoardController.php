<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\DisciplinaryBoard;
use App\DisciplinaryTeam;
use App\User;

class DisciplinaryBoardController extends Controller
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
        $request->validate([
            'user_id' => 'required|numeric',
            'disciplinary_team_id' => 'required|numeric',
            'effective_date' => 'required',
            'raison' => 'required',
            'location' => 'required',
            'decision' => 'required',
        ]);

        //We find the user
        $user = User::find($request->user_id);
        if ($user == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None user found");
            return response()->json($apiError, 400);
        }

        //we find the disciplinary team
        $team = DisciplinaryTeam::find($request->disciplinary_team_id);
        if ($team == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("DISCIPLINARY-TEAM_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None disciplinary team found");
            return response()->json($apiError, 400);
        }

        // les données de la requête sont valides
        $board = DisciplinaryBoard::create([
            'user_id' => $request->user_id,
            'disciplinary_team_id' => $request->disciplinary_team_id,
            'effective_date' => $request->effective_date,
            'raison' => $request->raison,
            'location' => $request->location,
            'decision' => $request->decision,

        ]);
        return response()->json($board, 201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'disciplinary_team_id' => 'required|numeric',
            'effective_date' => 'required',
            'raison' => 'required',
            'location' => 'required',
            'decision' => 'required',
        ]);

        $board = DisciplinaryBoard::find($id);

        $datas = $request->only([
            'user_id',
            'disciplinary_team_id',
            'effective_date',
            'raison',
            'location',
            'decision',
        ]);

        if ($board == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("DISCIPLINARY-BOARD_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None Disciplinary board found");
            return response()->json($apiError, 400);
        }
        // les données de la requête sont valides         
        $board->update($datas);
        return response()->json($board, 200);
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
