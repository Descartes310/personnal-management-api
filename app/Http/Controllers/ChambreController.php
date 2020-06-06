<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\APIError;
use App\Hotel;
use App\Chambre;
use Illuminate\Support\Str;

class ChambreController extends Controller
{
    public function createChambre(Request $req){
        $data = $req->only([
            'hotel_id',
            'reservation_id',
            'room_number',
            'room_state',
            'status',
            'amount'
        ]);

        $this->validate($data, [
            'hotel_id' => 'required|integer',
            'reservation_id' => 'required|integer',
            'room_number'=> 'required|integer',
            'room_state' => 'in :SIMPLE,VIP',
            'status' => 'in :FREE,TAKEN',
            'amount'=> 'required'
        ]);

        $hotel = Hotel::find($req->hotel_id);
        if (!$hotel) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $req->hotel_id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation1 = Reservation::find($req->reservation_id);
        if (!$reservation1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("RESERVATION_NOT_FOUND");
            $apiError->setMessage("Reservation with id " . $req->reservation_id . " not found");
    
            return response()->json($apiError,404);
        }

        $hotel = Hotel::find($req->hotel_id);
        if($hotel['room_total_number'] <= $req->room_number){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM-NUMBER_NOT_FOUND");
            $apiError->setMessage("Room number with id " . $req->room_number . " not incorrect");
    
            return response()->json($apiError,404);
        }

        $room = Chambre::find($req->room_number);
        $hotel = Chambre::find($req->hotel_id)->whereRoomNumber('$req_number');
        if(isset($room) && isset($hotel) && $req->status == 'TAKEN'){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM_ALREADY_TAKEN");
            $apiError->setMessage("Room already taken");
    
            return response()->json($apiError,404);
        }
        
        $chambre = new Chambre();
        $chambre->hotel_id = $data['hotel_id'];
        $chambre->reservation_id = $data['reservation_id'];
        $chambre->room_number = $data['room_number'];
        $chambre->room_state = $data['room_state'];
        $chambre->status = $data['status'];
        $chambre->amount = $data['amount'];
        
        $chambre->save();
        return response()->json($chambre);
    }

    
    public function updateChambre(Request $req, $id)
    {
        $chambre = Chambre::find($id);
        if (!$chambre) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROOM_NOT_FOUND");
            $apiError->setMessage("Room with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = $req->only([
            'hotel_id',
            'reservation_id',
            'room_number',
            'room_state',
            'status',
            'amount'
        ]);

        $this->validate($data, [
            'hotel_id' => 'required|integer',
            'reservation_id' => 'required|integer',
            'room_number'=> 'required|integer',
            'room_state' => 'in :SIMPLE,VIP',
            'status' => 'in :FREE,TAKEN',
            'amount'=> 'required'
        ]);

        $hotel = Hotel::find($req->hotel_id);
        if (!$hotel) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $req->hotel_id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation1 = Reservation::find($req->reservation_id);
        if (!$reservation1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("RESERVATION_NOT_FOUND");
            $apiError->setMessage("Reservation with id " . $req->reservation_id . " not found");
    
            return response()->json($apiError,404);
        }
        
        $hotel = Hotel::find($req->hotel_id);
        if($hotel['room_total_number'] <= $req->room_number){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM-NUMBER_NOT_FOUND");
            $apiError->setMessage("Room number with id " . $req->room_number . " incorrect");
    
            return response()->json($apiError,404);
        } 

        $room = Chambre::find($req->room_number);
        $hotel = Chambre::find($req->hotel_id)->whereRoomNumber('$req_number');
        if(isset($room) && isset($hotel) && $req->status == 'TAKEN'){
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("ROOM_ALREADY_TAKEN");
            $apiError->setMessage("Room already taken");
    
            return response()->json($apiError,404);
        }

        if (null !== $data['hotel_id']) $chambre->hotel_id = $data['hotel_id'];
        if (null !== $data['reservation_id']) $chambre->reservation_id = $data['reservation_id'];
        if (null !== $data['room_number']) $chambre->room_number = $data['room_number'];
        if (null !== $data['room_state']) $chambre->room_state = $data['room_state'];
        if (null !== $data['status']) $chambre->status = $data['status'];
        if (null !== $data['amount']) $chambre->amount = $data['amount'];

        $chambre->update();
        return response()->json($chambre);
    }


    public function destroyChambre($id)
    {
        if (!$chambre = Chambre::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("Hotel_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $chambre->delete();      
        return response()->json();
    }


    public function allChambre(Request $req){
        $data = Chambre::simplePaginate($req->has('limit')?$req->limit:15);
        return response()->json($data);
    }


    public function searchChambre(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',// on cherche q dans la table sur le champ field
            'field' => 'present'
        ]);

        $data = Chambre::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }


}
