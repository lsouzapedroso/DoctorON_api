<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowMedicPatientRequest;
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
    public function show(ShowMedicPatientRequest $request, $id_medico): JsonResponse
    {
        try {
            $medic = Medic::findOrFail($id_medico);

            $patients = Patient::whereHas('appointments', function ($query) use ($id_medico) {
                $query->where('medic_id', $id_medico);
            })
                ->with(['appointments' => function ($query) use ($id_medico) {
                    $query->where('medic_id', $id_medico)->select('id', 'patient_id', 'date', 'created_at', 'updated_at', 'deleted_at');
                }])
                ->get();

            if ($patients->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhum paciente encontrado para este médico.',
                    'medico' => $medic->name,
                ], 404);
            }

            $formattedPatients = $patients->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'nome' => $patient->name,
                    'cpf' => $patient->cpf,
                    'celular' => $patient->phone,
                    'created_at' => $patient->created_at,
                    'updated_at' => $patient->updated_at,
                    'deleted_at' => $patient->deleted_at,
                    'consulta' => $patient->appointments->isNotEmpty() ? [
                        'id' => $patient->appointments->first()->id,
                        'data' => $patient->appointments->first()->date,
                        'created_at' => $patient->appointments->first()->created_at,
                        'updated_at' => $patient->appointments->first()->updated_at,
                        'deleted_at' => $patient->appointments->first()->deleted_at,
                    ] : null,
                ];
            });

            return response()->json(
                $formattedPatients, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Médico não encontrado.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar pacientes:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar pacientes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
