<?php

namespace App\Http\Controllers;

use App\APIError;
use App\User;

class SanctionController extends Controller
{
    //methode create

    public function create(SanctionRequest $request)
    {
        $this->validate($request->all(), [
            'user_id' => 'required',
            'decision' => 'required',
            'user_id' => 'integer|required',
            'days' => 'integer|required',
            'raison' => 'string',
            'start_date' => 'date'
        ]);
       //les validations ce sont effectuees dans la classe SanctionRequest


        //test of user where user_id is...
        $user=User::find($request->user_id);
        //initialisation of subject
        $motif_table = null;

        if(!$user){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("SANCTION_USER");
            $apiError->setMessage("no user found with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 400);
        }

        if($request->subject!=null){
           // $table=strtoupper($request->subject);
            $table=$request->subject;
            if(!$this->istheTable($table)){
                $apiError = new APIError;
                $apiError->setStatus("400");
                $apiError->setCode("SANCTION_SUBJECT_NOT_FOUND");
                $apiError->setMessage("no subject found  with subject name $table ");
                $apiError->setErrors(['subject' => ["this subject it is not a name of table in the database"]]);
                return response()->json($apiError, 400);
            }
            else{

                $lien="\\App\\".($table);
                $table_class = new $lien();
                $motif_table = $table_class->find($request->subject_id);
                if(!$motif_table){
                    $apiError = new APIError;
                    $apiError->setStatus("406");
                    $apiError->setCode("SUBJECT_NOT_FOUND");
                    $apiError->setMessage("no subject_id ($request->subject_id)  was found  with subject name $table ");
                    $apiError->setErrors(['subject_id' => [" subject_id ($request->subject_id)  does not exist in $table in the database"]]);
                    return response()->json($apiError, 406);
                }
            }
        }

        $data=$request->all();
        $sanction = Sanction::create($data);
        return response()->json([
            "sanctions" => $sanction,
            "user" => $user,
            "raison" => $motif_table
        ]);
    }


    public function update(Request $request,$id)
    {
        //les validations ce sont effectuees dans la classe SanctionRequest
        $this->validate($request->all(), [
            'decision' => 'required',
            'user_id' => 'integer|required',
            'days' => 'integer|required',
            'raison' => 'string',
            'start_date' => 'date'
        ]);

        $data = $request->all();
        $sanctions = Sanction::find($id);

        if($sanctions == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("SANCTION_NOT_FOUND");
            $apiError->setMessage("no sanction foun with id ");
            return response()->json($apiError, 400);
        }

        if(User::find($request->user_id) == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("no user foun with id $request->user_id");
            $apiError->setErrors(['user_id' => ["this value is not exist"]]);
            return response()->json($apiError, 406);
        }

        $sanctions->update($data);
        return response()->json($data);
    }

    /**
     *
     * @author jiozangtheophane@gmail.com
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
                $sanctions = Sanction::where('raison', 'like', "%$s%")->orWhere('decision', 'like', "%$s%")->paginate($limit);
            } else {
                $sanctions = Sanction::where('raison', 'like', "%$s%")->orWhere('decision', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                $sanctions = Sanction::paginate($limit);
            } else {
                $sanctions = Sanction::all();
            }
        }

        return response() ->json($sanctions);
    }


    /**
     *
     * @author jiozangtheophane@gmail.com
     */
    public function find($id){

        $sanction = Sanction::find($id);
        if($sanction == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("FIND_SANCTION");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);

        }
        return response()->json($sanction);
    }
    /**
     *
     * @author jiozangtheophane@gmail.com
     */
    public function delete($id){

        $sanction = Sanction::find($id);
        if($sanction == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("DELETE_SANCTION");
            $unauthorized->setMessage("not found id.");

            return response()->json($unauthorized, 404);
        }
        else{
            $sanction->delete($sanction);
        }
        //$sanction = Sanction::get();
        return response()->json(null);
    }

    //on verifie si la subject passe existe
    public function istheTable($subject){
        //all the table witch can be able to make a sanction
        $tables = ["Career","DisciplinaryBoard","DisciplinaryTeam","License","Vacation","Training","NoteCriteria"];

        foreach($tables as $table){
            if($subject==$table){
                return true;
            }
        }
        return false;
    }
}
