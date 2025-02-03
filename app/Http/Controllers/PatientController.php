<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index()
    {
        try {
            $patients = Patient::all();

            return response()->json([
                $patients,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar paciente:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar o paciente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request): JsonResponse
    {
        try {
            $data = [
                'name' => $request->get('nome'),
                'cpf' => $request->get('cpf'),
                'phone' => $request->get('celular'),
            ];
            $patient = Patient::create($data);

            return response()->json([
                'id' => $patient->id,
                'name' => $patient->name,
                'cpf' => $patient->cpf,
                'celular' => $patient->phone,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'deleted_at' => $patient->deleted_at,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Erro ao cadastrar paciente:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao cadastrar o paciente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, $id_paciente)
    {
        try {
            $patient = Patient::findOrFail($id_paciente);
            $data = array_filter([
                'name' => $request->input('nome'),
                'phone' => $request->input('celular'),
            ], fn ($value) => ! is_null($value));
            if (empty($data)) {
                return response()->json([
                    'message' => 'Nenhuma informação foi fornecida para atualização.',
                ], 400);
            }
            $patient->fill($data);
            if (! $patient->isDirty()) {
                return response()->json([
                    'message' => 'Nenhuma alteração foi feita no paciente.',
                ], 200);
            }
            $patient->save();

            return response()->json([
                'id' => $patient->id,
                'nome' => $patient->name,
                'cpf' => $patient->cpf,
                'celular' => $patient->phone,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'deleted_at' => $patient->deleted_at,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Paciente não encontrado.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar paciente:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao atualizar o paciente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
