<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Contact;

class ContactController extends Controller
{

    //pour la recherche des contacts

    public function find($id){
        $contacts = Contact::find($id);
        if ($contacts == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("CONTACT_NOT_FOUND");
            $unauthorized->setMessage("Incorrect id or is not declared.");

            return response()->json($unauthorized, 404);
            // arrete le code et retourne une erreur, quand à abort, le 1er paramètre c'est le message et 2nd c'est le code http
        }
        return response()->json($contacts);
    }

    public function get(Request $req){

        $limit = $req->limit;
        $page = $req->page;
        $s = $req->s;

        if ($s) {
            if ($limit || $page) {
                $contacts = Contact::where('name', 'LIKE', "%  .$s. %")->paginate($limit);
            } else {
                $contacts = Contact::where('name', 'LIKE', "%  .$s. %")->get();
            }
        } else {
            if ($limit || $page) {
                $contacts = Contact::paginate($limit);
            } else {
                $contacts = Contact::all();
            }
        }

        return response()->json($contacts);

    }

    public function delete($id){
        $contacts = Contact::find($id);
        if($contacts == null) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("CONTACT_NOT_FOUND");
            $unauthorized->setMessage(" Contact Not found .");

            return response()->json($unauthorized, 404);
        }
        $contacts->delete($contacts);
        return response()->json($contacts);
    }
}
