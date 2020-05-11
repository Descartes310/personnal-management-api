<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\APIError;

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
            $apiError->setCode("Reverify  your id"); 
            $apiError->setMessage("Contract does not exist"); 
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
            $apiError->setCode("Reverify  your id"); 
            $apiError->setMessage("Contract does not exist"); 
            return response()->json($apiError, 404); 
        }           
        return $Contract ;
    }
   
    /**
     * Afshow all contracts
     */
    public function get($limit=null,$page=null , $s=null){
        $contracte=Contract::where('name', 'LIKE','%'.$s.'%'); 
        if($contract['data']==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("Reverify  your id"); 
            $apiError->setMessage("Contract does not exist"); 
            return response()->json($apiError, 404); 
        }
        $contract= $contracte->paginate($limit);
          $pagenumber=$contract->lastPage();
          if($pagenumber<$page){
            return response()->json([
              'La page que vous voulez affichez n\'existe pas'
              ], 404);
          }         
        return response()->json($contract);    
    } 

    

}
