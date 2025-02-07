<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class AppointmentsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/medicos/consulta",
     *     summary="Criar um novo agendamento de consulta",
     *     description="Agendar uma nova consulta para um médico e paciente em uma determinada data.",
     *     tags={"Médicos"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"medico_id", "paciente_id", "data"},
     *
     *             @OA\Property(property="medico_id", type="integer", example=1),
     *             @OA\Property(property="paciente_id", type="integer", example=5),
     *             @OA\Property(property="data", type="string", format="date-time", example="2025-02-08 14:00:00")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Consulta agendada com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="medico_id", type="integer", example=1),
     *             @OA\Property(property="paciente_id", type="integer", example=5),
     *             @OA\Property(property="data", type="string", format="date-time", example="2025-02-03T14:00:00"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-03T12:00:00"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-03T12:00:00"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Erro - Médico já tem uma consulta agendada neste horário",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Este médico já tem uma consulta agendada neste horário.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor ao tentar criar a consulta",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao agendar consulta."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro interno")
     *         )
     *     )
     * )
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $existingAppointment = Appointment::where('medic_id', $request->medico_id)
                ->where('date', $request->data)
                ->exists();

            if ($existingAppointment) {
                return response()->json([
                    'message' => 'Este médico já tem uma consulta agendada neste horário.',
                ], 422);
            }

            $appointment = Appointment::create([
                'medic_id' => $request->medico_id,
                'patient_id' => $request->paciente_id,
                'date' => date('Y-m-d H:i:s', strtotime($request->data)),
            ]);

            return response()->json([
                'id' => $appointment->id,
                'medico_id' => $appointment->medic_id,
                'paciente_id' => $appointment->patient_id,
                'data' => $appointment->date,
                'created_at' => $appointment->created_at,
                'updated_at' => $appointment->updated_at,
                'deleted_at' => $appointment->deleted_at,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao agendar consulta:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao agendar consulta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
