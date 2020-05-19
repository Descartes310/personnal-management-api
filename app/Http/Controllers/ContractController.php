<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\APIError;
use Barryvdh\DomPDF\Facade as PDF;


class ContractController extends Controller
{

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
    public function get(Request $req) {
        $s = $req->s;
        $page = $req->page;
        $limit = null;

        if ($req->limit && $req->limit > 0) {
            $limit = $req->limit;
        }

        if ($s) {
            if ($limit || $page) {
                $contracts = Contract::where('name', 'LIKE', '%' . $s . '%')->paginate($limit);
            } else {
                $contracts = Contract::where('name', 'LIKE', '%' . $s . '%')->get();
            }
        } else {
            if ($limit || $page) {
                $contracts = Contract::paginate($limit);
            } else {
                $contracts = Contract::all();
            }
        }

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
