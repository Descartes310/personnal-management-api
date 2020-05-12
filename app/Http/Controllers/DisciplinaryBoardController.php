<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DisciplinaryBoard;
use App\APIError;

class DisciplinaryBoardController extends Controller
{
    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s; 
        $page = $request->page; 
        $disciplinary_boards = DisciplinaryBoard::where('raison','LIKE','%'.$s.'%')->orWhere('decision','LIKE','%'.$s.'%')->orWhere('location','LIKE','%'.$s.'%')
                                                ->paginate($limit); 
        return response()->json($disciplinary_boards);
    }
    public function find($id){
        $disciplinary_board = DisciplinaryBoard::find($id);
        if($disciplinary_board == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DISCIPLINARY_BOARD_NOT_FOUND");
            $unauthorized->setMessage("No disciplinary board found with id $id");
            return response()->json($unauthorized, 404); 
        }
        return response()->json($disciplinary_board);
    }

    public function delete($id){
       $disciplinary_board = DisciplinaryBoard::find($id);
        if($disciplinary_board ==null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DISCIPLINARY_BOARD_NOT_FOUND");
            $unauthorized->setMessage("No disciplinary board found with id $id");
            return response()->json($unauthorized, 404); 
        }
        $disciplinary_board->delete($disciplinary_board);
        return null;
    }   
}
