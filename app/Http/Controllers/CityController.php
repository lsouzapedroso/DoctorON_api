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
        try {
            $cities = City::all();

            return response()->json(
                $cities, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar cidades:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar cidades.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(ShowCityMedicsRequest $request, $id_cidade): JsonResponse
    {
        try {
            $city = City::findOrFail($id_cidade);
            $medics = Medic::where('city_id', $id_cidade)->get();
            if ($medics->isEmpty()) {
                return response()->json([
                    'cidade' => $city->name,
                    'message' => 'Nenhum médico encontrado nesta cidade.',
                ], 404);
            }
            $medicosFormatados = $medics->map(function ($medic) {
                return [
                    'id' => $medic->id,
                    'nome' => $medic->name,
                    'especialidade' => $medic->specialization,
                    'cidade_id' => $medic->city_id,
                ];
            });

            return response()->json(
                $medicosFormatados, 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Cidade não encontrada.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar médicos por cidade:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao carregar médicos da cidade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
