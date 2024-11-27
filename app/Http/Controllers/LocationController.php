<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();

        return response()->json($cities);
    }
    public function getStates()
    {
        $states = State::all();

        return response()->json($states);
    }
}
