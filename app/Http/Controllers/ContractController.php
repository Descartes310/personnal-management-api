<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\User;
use App\APIError;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{


    /* fonction de création d'un contrat */
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'type' => 'required',
            'name' => 'required',
            'free_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
           // 'file' => 'required',
        ]);

        //on cherche l'utilisateur
        $user = User::find($request->user_id);

        if ($user == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None user found");
            return response()->json($apiError, 400);
        }

        $namepdf =$request->name.'_'.$request->title.'.pdf';
        $pdf = PDF::loadHtml($request->terms);
        $pdf->save(public_path('/uploads/contracts/').$namepdf);

        // les données de la requête sont valides
        $contract = Contract::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'name' => $request->name,
            'title' => $request->title,
            'terms' => $request->terms,
            'free_days' => $request->free_days,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'file' =>  '/uploads/contracts/'.$namepdf,

        ]);
        return response()->json($contract, 201);
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
            'type' => 'required',
            'name' => 'required',
            'free_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $contract = Contract::find($id);

        $datas = $request->only([
            'user_id',
            'type',
            'name',
            'title',
            'terms',
            'free_days',
            'start_date',
            'end_date',
            'is_active',
        ]);


        if ($contract == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None Contract found");
            return response()->json($apiError, 400);
        } else {
            //$file = $request->file('file');
            $namepdf =$request->name.'_'.$request->title.'.pdf';
            $pdf = PDF::loadHtml($request->terms);
            $pdf->save(public_path('/uploads/contracts/').$namepdf);
            $datas['file'] = '/uploads/contracts/'.$namepdf;
            //Delete old contract file if exist
            if ($contract->file) {
                $oldFilePath = str_replace(url('/'), public_path(), $contract->file);
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            // les données de la requête sont valides

            $contract->update($datas);

            return response()->json($contract, 200);
        }
    }


    /**
     * Delete Contract
     */
    public function delete(Request $request, $id){
        $Contract = Contract::find($id);
        if($Contract==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_PAGE_NOT_FOUND");
            $apiError->setMessage("page does not exist");
            return response()->json($apiError, 404);
        }
        $Contract = Contract::findOrFail($id);
        $Contract->delete();
        return 200;
    }


    /**
     * Show contract
     */
    public function find($id){
        $Contract= Contract::find($id);
        if($Contract==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_PAGE_NOT_FOUND");
            $apiError->setMessage("page does not exist");
            return response()->json($apiError, 404);
        }
        return $Contract ;
    }

    /**
     * Afshow all contracts
     * When page is not found, an empty array is returned
     */
    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s;
        $page = $request->page;
        $contracts = Contract::where('type', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);

        return response()->json($contracts);
    }

    /**
     * @author ADAMU ALIYU
     */

    public function printPDF($id){

        $contract= Contract::find($id);
        if($contract==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_PAGE_NOT_FOUND");
            $apiError->setMessage("page does not exist");
            return response()->json($apiError, 404);
        }
        return response()->json(url($contract->file));
    }
}
