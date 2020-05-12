<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Career;
class CareerController extends Controller
{
    //recuperatin de tout les contact
    public function get(Request $request)
    {
        //recuperation de toutes les career dans la base de donnee
        $limit=$request->limit;
        $page=$request->page;
        $motclef=$request->s;

        $user=new Career();
        //aucun parametre n'est present
        if(!$limit && !$page){
            $career = DB::table('careers')->get();
            return response()->json($career,200);

        }
        

        return response()->json("fax",200);
    }

    
}
