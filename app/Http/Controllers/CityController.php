<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowCityMedicsRequest;
use App\Models\City;
use App\Models\Medic;
use Illuminate\Http\JsonResponse;

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

    public function show(ShowCityMedicsRequest $request, $id_cidade): JsonResponse
    {
        $city = City::findorfail($id_cidade);

        $medics = Medic::where('city_id', $id_cidade)->get();

        return response()->json(
            [
                'cidade' => $city->name,
                'medic' => $medics,
            ]);
    }
}
