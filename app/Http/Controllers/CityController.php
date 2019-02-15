<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function showAll()
    {
        return response()->json(City::all());
    }

}