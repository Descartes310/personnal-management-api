<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APIError;
use App\Contact;

class contactController extends Controller
{

    public function saveContact(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'email',
            'gender' => 'in:M,F',
            'type' => 'in:INTERNAL,EXTERNAL',
            'nature' => 'in:MORAL,PHYSIC'
        ]);
        $file = $request->file('picture');
        $path = null;
        if($file != null){
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/contacts";
            $destinationPath = public_path($relativeDestination);
            $safeName = str_replace(' ','_',$request->name).time().'.'.$extension;
            $file->move($destinationPath, $safeName);
            $path = url("$relativeDestination/$safeName");
        }
        $contact = Contact::create([
            'name' => $request->name,
            'type' => $request->type,
            'nature' => $request->nature,
            'email' => $request->email,
            'gender' => $request->gender,
            'picture' => $path,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'phone3' => $request->phone3,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'fax' => $request->fax,
            'bp' => $request->bp,
            'description' => $request->description,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'website' => $request->website
        ]);

        
        return response()->json($contact);
    }

    public function updateContact(Request $request, $id){
        
        $request->validate([
            'name' => 'string',
            'type' => 'string',
            'nature' => 'string',
            'email' => 'email',
            'gender' => 'in:M,F',
            'type' => 'in:INTERNAL,EXTERNAL',
            'nature' => 'in:MORAL,PHYSIC'

        ]);
        $contact = Contact::find($id);
        if($contact != null){
            $file = $request->file('picture');
            $path = null;
            if($file != null){
                $extension = $file->getClientOriginalExtension();
                $relativeDestination = "uploads/contacts";
                $destinationPath = public_path($relativeDestination);
                $safeName = str_replace(' ','_',$request->name).time().'.'.$extension;
                $file->move($destinationPath, $safeName);
                $path = url("$relativeDestination/$safeName");
                //Delete old contact image if exxists
                if ($contact->picture) {
                    $oldImagePath = str_replace(url('/'), public_path(), $contact->picture);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                $contact->picture = $path;
            }
          
            $contact->update($request->only([
                'name',
                'type',
                'nature',
                'email',
                'gender',
                'phone1',
                'phone2',
                'phone3',
                'whatsapp',
                'facebook',
                'fax',
                'bp',
                'description',
                'twitter',
                'linkedin',
                'website'
            ]));
  
          return response()->json($contact);
        } else {
            $errorcode = new APIError;
            $errorcode->setStatus("401");
            $errorcode->setCode("AUTH_LOGIN");
            $errorcode->setMessage("This contact does not exist");

            return response()->json($errorcode, 401);
        }
    }

    
}
