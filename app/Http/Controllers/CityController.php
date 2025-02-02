<?php

namespace App\Http\Controllers;

use App\Models\City;

class CityController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index()
    {
        $cities = City::all();

        return $cities;
    }
}
