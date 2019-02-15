<?php

namespace App\Http\Controllers;

use App\State;
use App\City;
use Illuminate\Http\Request;
use App\Exceptions\NotFoundException;
class StateController extends Controller
{
    public function showAll()
    {
        return response()->json(State::all());
    }
    public function showCities($id)
    {
        $state = State::find($id);
        if($state == null ){
            throw new NotFoundException;
        }
        $cities = City::where('state_id',$state->id)->get();

        return response()->json($cities);
    }


}