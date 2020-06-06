<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hotel;
use App\APIError;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    //
    public function createHotel(Request $req){
        $data = $req->only([
            'name',
            'class',
            'location',
            'director_name',
            'room_total_number'
        ]);

        $this->validate($data, [
            'name' => 'required|string',
            'class'=> 'required|string',
            'location' => 'required|string',
            'director_name' => 'required|string',
            'room_total_number' => 'required|integer'
        ]);

        $data['slug'] = Str::slug($req->name) . time();
        $hotel = new Hotel();
        $hotel->name = $data['name'];
        $hotel->class = $data['class'];
        $hotel->slug = $data['slug'];
        $hotel->location = $data['location'];
        $hotel->director_name = $data['director_name'];
        $hotel->room_total_number = $data['room_total_number'];
        
        $hotel->save();
        return response()->json($hotel);
    }

    public function updateHotel(Request $req, $id)
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $data = $req->only([
            'name',
            'class',
            'location',
            'director_name',
            'room_total_number'
        ]);

        $this->validate($data, [
            'name' => 'required',
            'class'=> 'required',
            'location' => 'required',
            'director_name' => 'required',
            'room_total_number' => 'required|integer'
        ]);

        $data['slug'] = Str::slug($req->name) . time();

        if (null !== $data['name']) $hotel->name = $data['name'];
        if (null !== $data['slug']) $hotel->slug = $data['slug'];
        if (null !== $data['class']) $hotel->class = $data['class'];
        if (null !== $data['location']) $hotel->location = $data['location'];
        if (null !== $data['director_name']) $hotel->director_name = $data['director_name'];
        if (null !== $data['room_total_number']) $hotel->room_total_number = $data['room_total_number'];
       
        $hotel->update();
        return response()->json($hotel);
    }

    public function destroyHotel($id)
    {
        if (!$hotel = Hotel::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("HOTEL_NOT_FOUND");
            $apiError->setMessage("Hotel with id " . $id . " not found");
    
            return response()->json($apiError,404);
        }

        $hotel->delete();      
        return response()->json();
    }


    public function allHotel(Request $req){
        $data = Hotel::simplePaginate($req->has('limit')?$req->limit:15);
        return response()->json($data);
    }


    public function searchHotel(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',// on cherche q dans la table sur le champ field
            'field' => 'present'
        ]);

        $data = Hotel::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit')?$req->limit:15);

        return response()->json($data);
    }


}
