<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use App\APIError;
use App\Sanction;
use App\User;

class SanctionController extends Controller
{
    //methode create

    public function create(Request $request)
    {
        $this->validate($request->all(), [
            'user_id' => 'required',
        ]);
        //test of user where user_id is...
        $user=User::find($request->user_id);
        //initialisation of subject
        $subject=null;

        if($user==null) {
            $apiError = new APIError;
            $apiError->setStatus("400"); 
            $apiError->setCode("SANCTION_USER"); 
            $apiError->setMessage("no user foun with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 400);
        }

        if($request->subject!=null){
           // $table=strtoupper($request->subject);
            $table=$request->subject;
            if(!$this->istheTable($table)){
                $apiError = new APIError;
                $apiError->setStatus("400"); 
                $apiError->setCode("SANCTION_SUBJECT"); 
                $apiError->setMessage("no subject found  with subject name $table ");
                $apiError->setErrors(['subject' => ["this subject it is not a name of table in the database"]]);
                return response()->json($apiError, 400);
            }
            else{
                
                $lien="\\App\\".($table);
                $subject=new $lien();
                $subject=$subject->find($request->subject_id);
                if($subject==null){
                    $apiError = new APIError;
                    $apiError->setStatus("406"); 
                    $apiError->setCode("NO SUBJECT ID VALUE"); 
                    $apiError->setMessage("no subject_id ($request->subject_id)  was found  with subject name $table ");
                    $apiError->setErrors(['subject_id' => [" subject_id ($request->subject_id)  does not exist in $table in the database"]]);
                    return response()->json($apiError, 406);
                }
            }            
        }

        $data=$request->all();
        $sanction = Sanction::create($data);
        return response()->json(
            ["sanctions"=>$sanction,
            "user"=>$user,
            "raison"=>$subject]);
    }

    
    public function update(Request $request,$id)
    {
        
        $request->validate([
            
            'user_id' => 'required'
        ]);

        $data = $request->all();
        $sanctions = Sanction::find($id);
        
        if($sanctions == null) {
            $apiError = new APIError;
            $apiError->setStatus("400"); 
            $apiError->setCode("UNKNOW SANTION"); 
            $apiError->setMessage("no sanction foun with id ");
            return response()->json($apiError, 400);
        }

        if(User::find($request->user_id) == null) {
            $apiError = new APIError;
            $apiError->setStatus("400"); 
            $apiError->setCode("SANCTION_USER"); 
            $apiError->setMessage("no user foun with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 406);
        }
        
        $sanctions->update($data);
        return response()->json($data);
    }

    //on verifie si la subject passe existe
    public function istheTable($subject){
        //all the table witch can be able to make a sanction
        $tables=["AssignmentType","Career","DisciplinaryBoard","DisciplinaryTeam","License","ProSituation","NoteCriteria"];
        
        foreach($tables as $table){
            if($subject==$table){
                return true;
            }
        }
        return false;
    }
}