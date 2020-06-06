<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserReservation;
use App\User;
use App\Reservation;
use App\APIError;
use Illuminate\Support\Str;

class UserReservationController extends Controller
{
    public function createUserReservation(Request $req){
        $data = $req->only([
            'user_id',
            'reservation_id'
        ]);

        $this->validate($data, [
            'user_id' => 'required|integer',
            'reservation_id' => 'required|integer'
        ]);

        $user1 = User::find($req->user_id);
        if (!$user1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("User with id " . $req->user_id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation1 = Reservation::find($req->reservation_id);
        if (!$reservation1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_RESERVATION_NOT_FOUND");
            $apiError->setMessage("UserReservation with id " . $req->reservation_id . " not found");
    
            return response()->json($apiError,404);
        }
        
        $user_reservation = new UserReservation();
        $user_reservation->user_id = $data['user_id'];
        $user_reservation->reservation_id = $data['reservation_id'];
        
        $user_reservation->save();
        return response()->json($user_reservation);
    }


    public function updateUserReservation(Request $req, $id)
    {
        $user_reservation = UserReservation::find($id);
        if (!$user_reservation) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_RESERVATION_NOT_FOUND");
            $apiError->setMessage("UserReservation with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = $req->only([
            'user_id',
            'reservation_id'
        ]);

        $this->validate($data, [
            'user_id' => 'required|integer',
            'reservation_id' => 'required|integer'
        ]);

        $user1 = User::find($req->user_id);
        if (!$user1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("User with id " . $req->user_id . " not found");
    
            return response()->json($apiError,404);
        }

        $reservation1 = Reservation::find($req->reservation_id);
        if (!$reservation1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_RESERVATION_NOT_FOUND");
            $apiError->setMessage("UserReservation with id " . $req->reservation_id . " not found");
    
            return response()->json($apiError,404);
        }

        if (null !== $data['user_id']) $user_reservation->user_id = $data['user_id'];
        if (null !== $data['reservation_id']) $user_reservation->reservation_id = $data['reservation_id'];
       
        $user_reservation->update();
        return response()->json($user_reservation);
    }


    public function destroyUserReservation($id)
    {
        if (!$user_reservation = UserReservation::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_RESERVATION_NOT_FOUND");
            $apiError->setMessage("UserReservation with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $user_reservation->delete();      
        return response()->json();
    }


    public function allUserReservation(Request $req){
        $data = UserReservation::simplePaginate($req->has('limit')?$req->limit:15);
        return response()->json($data);
    }


    public function searchUserReservation(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',// on cherche q dans la table sur le champ field
            'field' => 'present'
        ]);

        $data = UserReservation::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }



    public function findUserXReservation(Request $req, $id)
    {   
        $user1 = User::find($req->user_id);
        if (!$user1) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("User with id " . $req->user_id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = UserReservation::select('user_reservation.*', 'user_reservation.id as user_reservation_id',
         'reservations.*', 'reservations.id as reservations_id', 'rooms.*', 'rooms.id as rooms_id',
         'hotels.*', 'hotels.id as hotels_id')
            ->join('reservations', 'user_reservation.reservation_id', '=', 'reservations.id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->where('user_reservation.user_id', '=', $id)
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }

}
