<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Contract;
use App\User;

class ContractController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /* fonction de création d'un contrat */
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'type' => 'required',
            'file' => 'required',
        ]);
        //on cherche l'utilisateur
        $user = User::find($request->user_id);

        if ($user == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("NF_User");
            $apiError->setMessage("Something wrong with your request! None user found");
            return response()->json($apiError, 400);
        }

        //    Enregistrement du chemin du Fichier file
        if ($file = $request->file('file')) {
            $request->validate(['file' => 'file|mimes:pdf,doc,ppt,xls,rtf']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/contracts";
            $destinationPath = public_path($relativeDestination);
            $safeName = str_replace(' ', '_', $request->email) . time() . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $data['file'] = url("$relativeDestination/$safeName");
        }


        // les données de la requête sont valides
        $input = $request->all();
        $contract = Contract::create($input);
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
            'file' => 'required',
        ]);

        $contract = Contract::find($id);

        if ($contract == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("NF_ContractId");
            $apiError->setMessage("Something wrong with your request! None Contract found");
            return response()->json($apiError, 400);
        }

        // les données de la requête sont valides
        $request->all();
        $contract = Contract::findOrFail($id);
        $contract->update($request->all());
        return response()->json($contract, 200);
    }

    /**
     * Delete Contract
     */
    public function delete(Request $request, $id){
        $Contract = Contract::find($id);
        if($Contract==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_NOT_FOUND"); 
            $apiError->setMessage("Contract does not exist"); 
            return response()->json($apiError, 404);       
        }
        $Contract = Contract::findOrFail($id);
        $Contract->delete();
        return 200;
    }

    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s; 
        $page = $request->page; 
        $prosituations = Contract::where('name','LIKE','%'.$s.'%')
                                       ->paginate($limit); 
        return response()->json($prosituations);
    }
  
    /**
     * Show contract
     */
    public function find($id){
        $Contract= Contract::find($id); 
        if($Contract==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("CONTRACT_NOT_FOUND"); 
            $apiError->setMessage("Contract does not exist"); 
            return response()->json($apiError, 404); 
        }           
        return $Contract ;
    }
}