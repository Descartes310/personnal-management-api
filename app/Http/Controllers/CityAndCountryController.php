<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityAndCountryController extends Controller
{
    public function cities() {
        $cities = City::limit(30)->get();
        return response()->json($cities);
    }
}
