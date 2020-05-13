<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VacationType;
use App\APIError;

class VacationTypeController extends Controller
{
    //  
    /**
     * Delete VacationType
     */
    public function delete(Request $request, $id){
        $vacationtype = VacationType::find($id);
        if($vacationtype==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATION_TYPE_PAGE_NOT_FOUND"); 
            $apiError->setMessage("page does not exist"); 
            return response()->json($apiError, 404);       
        }
        $vacationtype = VacationType::findOrFail($id);
        $vacationtype->delete();
        return 200;
    }

     /**
     * Show VacationType
     */
    public function find($id){
        $vacationtype= VacationType::find($id); 
        if($vacationtype==null){
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("VACATION_TYPE_PAGE_NOT_FOUND"); 
            $apiError->setMessage("page does not exist"); 
            return response()->json($apiError, 404); 
        }           
        return $vacationtype ;
    }

    /**
     * show all VacationType
     */
    public function get(Request $request) {

        $limit = $request->limit;
        $page = $request->page;
        $s = $request->s;

        if($limit != null && $s != null && $page != null){
      
            $vacationtype = VacationType::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            $pagenumber=$vacationtype->lastPage();
            if($pagenumber < $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("VACATION_TYPE_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($vacationtype);     
          }
    
          if($limit != null && $s == null && $page != null){
            
            $vacationtype = VacationType::paginate($limit);
            $pagenumber=$vacationtype->lastPage();
    
            if($pagenumber < $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("VACATION_TYPE_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($vacationtype); 
          
          }
    
          if($limit != null && $s != null && $page == null){
            
            $vacationtype = VacationType::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            return response()->json($vacationtype);
          
          }
    
          if($limit != null && $s == null && $page == null){
            
            $vacationtype = VacationType::paginate($limit);
            return response()->json($vacationtype);
          
          }
    
          if($limit == null && $s != null && $page == null){
            
            $limit=2;
            $vacationtype = VacationType::where('name', 'LIKE','%'.$s.'%')->get();
            return response()->json($vacationtype);
          
          }
    
          if($limit == null && $s != null && $page != null){
            
            $limit=2;
            $vacationtype = VacationType::where('name', 'LIKE','%'.$s.'%')->paginate($limit);
            $pagenumber=$vacationtype->lastPage();
            
            if($pagenumber < $page) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("VACATION_TYPE_PAGE_NOT_FOUND"); 
                $apiError->setMessage("page does not exist"); 
                return response()->json($apiError, 404);
            }
            return response()->json($vacationtype);     
          }
    
          if($limit == null && $s == null && $page == null){
            
            $vacationtype = VacationType::all();
            return response()->json($vacationtype);
          
          }
    
          if($limit == null && $s == null && $page != null){
            
            $vacationtype = VacationType::all();
            return response()->json($vacationtype);
          
          }  
    }

}

