<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowMadicPatientRequest;
use App\Http\Requests\StoreMedicRequest;
use App\Models\Medic;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class MedicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $medics = Medic::all();

            return response()->json(
                $medics, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar os medicos:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar os medicos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicRequest $request): JsonResponse
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Erro ao cadastrar medico:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao cadastrar o medico.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowMadicPatientRequest $request, $id_medico): JsonResponse
    {
        try {
            $medic = Medic::findorfail($id_medico);

            $patients = Patient::where('medic_id', $id_medico)->get();

            return response()->json([
                $patients,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar pacientes:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar pacientes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
