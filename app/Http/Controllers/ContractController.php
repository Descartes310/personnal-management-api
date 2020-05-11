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
     */
    public function get(Request $request) {

        $limit = $request->limit;
        $page = $request->page;
        $s = $request->s;

        if($limit != null && $s != null && $page != null){
      
            $Contract = Contract::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            $pagenumber=$Contract->lastPage();
    
            if($pagenumber > $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("CONTRACT_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($Contract);     
          }
    
          if($limit != null && $s == null && $page != null){
            
            $Contract = Contract::paginate($limit);
            $pagenumber=$Contract->lastPage();
    
            if($pagenumber > $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("CONTRACT_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($Contract); 
          
          }
    
          if($limit != null && $s != null && $page == null){
            
            $Contract = Contract::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            return response()->json($Contract);
          
          }
    
          if($limit != null && $s == null && $page == null){
            
            $Contract = Contract::paginate($limit);
            return response()->json($Contract);
          
          }
    
          if($limit == null && $s != null && $page == null){
            
            $limit=2;
            $Contract = Contract::where('name', 'LIKE','%'.$s.'%')->get();
            return response()->json($Contract);
          
          }
    
          if($limit == null && $s != null && $page != null){
            
            $limit=2;
            $Contract = Contract::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            $pagenumber=$Contract->lastPage();
            
            if($pagenumber < $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("CONTRACT_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($Contract);     
          }
    
          if($limit == null && $s == null && $page == null){
            
            $Contract = Contract::all();
            return response()->json($Contract);
          
          }
    
          if($limit == null && $s == null && $page != null){
            
            $Contract = Contract::all();
            return response()->json($Contract);
          
          }
       
          
        } 
    }
