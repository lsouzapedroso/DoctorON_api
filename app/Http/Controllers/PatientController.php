<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/pacientes",
     *     summary="Cadastrar um novo paciente",
     *     description="Cadastra um novo paciente no sistema.",
     *     tags={"Pacientes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"nome", "cpf", "celular"},
     *
     *             @OA\Property(property="nome", type="string", example="Maria Souza"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *             @OA\Property(property="celular", type="string", example="+55 11 98765-4321")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Paciente cadastrado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Maria Souza"),
     *             @OA\Property(property="cpf", type="string", example="849.570.310-61"),
     *             @OA\Property(property="celular", type="string", example="+55 11 98765-4321"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar o paciente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar o paciente."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/pacientes/{id}",
     *     summary="Atualizar informações de um paciente",
     *     description="Atualiza os dados de um paciente existente.",
     *     tags={"Pacientes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do paciente",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="nome", type="string", example="Maria Souza"),
     *             @OA\Property(property="celular", type="string", example="+55 11 98765-4321")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paciente atualizado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Maria Souza"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *             @OA\Property(property="celular", type="string", example="+55 11 98765-4321"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Nenhuma informação foi fornecida para atualização",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Nenhuma informação foi fornecida para atualização.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Paciente não encontrado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Paciente não encontrado."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar o paciente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar o paciente."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
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
