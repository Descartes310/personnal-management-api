<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\User;
use App\APIError;
use Barryvdh\DomPDF\Facade as PDF;


class ContractController extends Controller
{


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
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! None user found");
            return response()->json($apiError, 400);
        }

        //    Enregistrement du chemin du Fichier file
        $file = $request->file('file');
        $path = null;
        if ($file != null) {
            $request->validate(['file' => 'file|mimes:pdf,doc,ppt,xls,rtf']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/contracts";
            $destinationPath = public_path($relativeDestination);
            $safeName = str_replace(' ', '_', $request->email) . time() . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $path['file'] = "/uploads/contracts/".$safeName;
        }


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
            'file' => $path['file'],

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
            'file' => 'required',
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
            $file = $request->file('file');
            if ($file != null) {
                $request->validate(['file' => 'file|mimes:pdf,doc,ppt,xls,rtf']);
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/contracts";
                $destinationPath = public_path($relativeDestination);
                $safeName = str_replace(' ', '_', $request->email) . time() . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $datas['file'] = "/uploads/contracts/".$safeName;
                //Delete old contract file if exist
                if ($contract->file) {
                    $oldFilePath = str_replace(url('/'), public_path(), $contract->file);
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
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

    public function printPDF(Request $request, $id){

        $name = $this->find($id)->name;
        $title = $this->find($id)->title;
        $namepdf = $name . ' ' . $title.'.pdf';

        $data = $this->find($id)->terms;

        $pdf = PDF::loadHtml($data);
        return $pdf->download($namepdf);
    }
}
