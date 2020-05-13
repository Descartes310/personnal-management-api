<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DisciplinaryBoard;
use App\APIError;
use App\DisciplinaryTeam;
use App\User;


class DisciplinaryBoardController extends Controller
{


    public function get(Request $request)
    {
        $limit = $request->limit;
        $page = $request->page;
        $s = $request->s;
        if ($s) {
            if ($limit || $page) {
                $disciplinary_boards = DisciplinaryBoard::where('raison', 'LIKE', '%' . $s . '%')
                    ->orWhere('decision', 'LIKE', '%' . $s . '%')
                    ->orWhere('location', 'LIKE', '%' . $s . '%')
                    ->paginate($limit);
            } else {
                $disciplinary_boards = DisciplinaryBoard::where('raison', 'LIKE', '%' . $s . '%')
                    ->orWhere('decision', 'LIKE', '%' . $s . '%')
                    ->orWhere('location', 'LIKE', '%' . $s . '%')
                    ->get();
            }
        } else {
            if ($limit || $page) {
                $disciplinary_boards = DisciplinaryBoard::paginate($limit);
            } else {
                $disciplinary_boards = DisciplinaryBoard::all();
            }
        }

        return response()->json($disciplinary_boards);
    }


    public function find($id)
    {
        $disciplinary_board = DisciplinaryBoard::find($id);
        if ($disciplinary_board == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DISCIPLINARY_BOARD_NOT_FOUND");
            $unauthorized->setMessage("No disciplinary board found with id $id");
            return response()->json($unauthorized, 404);
        }
        return response()->json($disciplinary_board);
    }


    public function delete($id)
    {
        $disciplinary_board = DisciplinaryBoard::find($id);
        if ($disciplinary_board == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DISCIPLINARY_BOARD_NOT_FOUND");
            $unauthorized->setMessage("No disciplinary board found with id $id");
            return response()->json($unauthorized, 404);
        }
        $disciplinary_board->delete($disciplinary_board);
        return null;
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
            $apiError->setCode("DISCIPLINARY_TEAM_NOT_FOUND");
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|numeric|exists:App\User,id',
            'disciplinary_team_id' => 'required|numeric|exists:App\DisciplinaryTeam,id',
            'effective_date' => 'required|date',
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
            $apiError->setCode("DISCIPLINARY_BOARD_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None Disciplinary board found");
            return response()->json($apiError, 400);
        }
        // les données de la requête sont valides
        $board->update($datas);
        return response()->json($board, 200);
    }


}
