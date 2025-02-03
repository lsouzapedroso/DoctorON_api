<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowMedicPatientRequest;
use App\Http\Requests\StoreMedicRequest;
use App\Models\Medic;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/medicos",
     *     summary="Listar todos os médicos",
     *     description="Retorna uma lista de todos os médicos cadastrados. Permite busca por nome.",
     *     tags={"Médicos"},
     *
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Parte do nome do médico para busca (ignora 'Dr.' e 'Dra.')",
     *         required=false,
     *
     *         @OA\Schema(type="string", example="João")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de médicos retornada com sucesso",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Dr. João Silva"),
     *                 @OA\Property(property="specialization", type="string", example="Cardiologista"),
     *                 @OA\Property(property="city_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-03T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-03T12:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar os médicos",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao listar os médicos."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Medic::query();

            $columnName = 'name';

            if ($request->has('nome') && ! empty($request->input('nome'))) {
                $nome = $request->input('nome');

                $nameWithoutPrefix = preg_replace('/^Dr\. |^Dra\. /i', '', $nome);

                $query->whereRaw("REPLACE(REPLACE($columnName, 'Dr. ', ''), 'Dra. ', '') LIKE ?", ["%$nameWithoutPrefix%"]);
            }
            $query->orderByRaw("REPLACE(REPLACE($columnName, 'Dr. ', ''), 'Dra. ', '') ASC");
            $medicos = $query->get();

            return response()->json($medicos, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar os médicos:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar os médicos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/medicos",
     *     summary="Cadastrar um novo médico",
     *     description="Cadastra um novo médico no sistema.",
     *     tags={"Médicos"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"nome", "especialidade", "cidade_id"},
     *
     *             @OA\Property(property="nome", type="string", example="Dr. João Silva"),
     *             @OA\Property(property="especialidade", type="string", example="Cardiologia"),
     *             @OA\Property(property="cidade_id", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Médico cadastrado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Dr. João Silva"),
     *             @OA\Property(property="especialidade", type="string", example="Cardiologia"),
     *             @OA\Property(property="cidade_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-01T12:00:00Z"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar o médico",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar o médico."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/medicos/{id_medico}/pacientes",
     *     summary="Listar pacientes de um médico",
     *     description="Retorna todos os pacientes que possuem consultas agendadas e/ou realizadas com o médico. Permite filtros opcionais.",
     *     tags={"Médicos"},
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *         name="id_medico",
     *         in="path",
     *         description="ID do médico",
     *         required=true,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="apenas-agendadas",
     *         in="query",
     *         description="Se verdadeiro, retorna apenas consultas futuras",
     *         required=false,
     *
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Nome do paciente para busca",
     *         required=false,
     *
     *         @OA\Schema(type="string", example="Carlos")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes retornada com sucesso",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Carlos Souza"),
     *                 @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *                 @OA\Property(property="celular", type="string", example="(11) 98765-4321"),
     *                 @OA\Property(property="consulta", type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="data", type="string", format="date-time", example="2024-02-05T10:00:00Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Médico não encontrado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Médico não encontrado.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar pacientes",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao listar pacientes."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function listMedicPatient(ShowMedicPatientRequest $request, $id_medico): JsonResponse
    {
        try {
            $medic = Medic::findOrFail($id_medico);

            $query = Patient::whereHas('appointments', function ($query) use ($id_medico, $request) {
                $query->where('medic_id', $id_medico);

                if ($request->has('apenas-agendadas') && filter_var($request->input('apenas-agendadas'), FILTER_VALIDATE_BOOLEAN)) {
                    $query->where('date', '>=', now());
                }
            });

            if ($request->has('nome') && ! empty($request->input('nome'))) {
                $nome = $request->input('nome');
                $query->where('name', 'LIKE', "%$nome%");
            }

            $patients = $query->with(['appointments' => function ($query) use ($id_medico) {
                $query->where('medic_id', $id_medico)
                    ->select('id', 'patient_id', 'date', 'created_at', 'updated_at', 'deleted_at')
                    ->orderBy('date', 'asc');
            }])->get();

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
                    'consulta' => $patient->appointments->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'data' => $appointment->date,
                            'created_at' => $appointment->created_at,
                            'updated_at' => $appointment->updated_at,
                            'deleted_at' => $appointment->deleted_at,
                        ];
                    }),
                ];
            });

            return response()->json($formattedPatients, 200);
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
