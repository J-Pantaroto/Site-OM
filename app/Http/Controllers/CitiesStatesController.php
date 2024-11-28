<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\State;
use App\Models\User;
use App\Models\City;


class CitiesStatesController extends Controller
{
    public function edit()
    {
        $states = State::all();
        $cities = City::where('state_id', Auth::user()->state_id)->get();
        return view('profile', compact('states', 'cities'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
        ]);
        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'state_id' => $request->state,
            'city_id' => $request->city,
        ]);

        return redirect()->route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }
    public function getCitiesByState($stateAbbreviation)
    {
        $state = State::where('abbreviation', $stateAbbreviation)->first();
        if (!$state) {
            return response()->json(['error' => 'Estado não encontrado'], 404);
        }
        $cities = City::where('state_id', $state->id)->orderBy('name')->get(['ibge_code', 'name']);
        return response()->json($cities);
    }
}
