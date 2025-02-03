<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicRequest;
use App\Models\Medic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medics = Medic::all();

        return $medics;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicRequest $request): JsonResponse
    {
        $data = [
            'name' => $request->get('nome'),
            'specialization' => $request->get('especialidade'),
            'city_id' => $request->get('cidade_id'),
        ];
        $medic = Medic::create($data);

        return response()->json([
            'id' => $medic->id,
            'nome' => $medic->name,
            'especialidade' => $medic->specialization,
            'cidade_id' => $medic->city_id,
            'created_at' => $medic->created_at,
            'updated_at' => $medic->updated_at,
            'deleted_at' => $medic->deleted_at,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medic $medic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medic $medic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medic $medic)
    {
        //
    }
}
