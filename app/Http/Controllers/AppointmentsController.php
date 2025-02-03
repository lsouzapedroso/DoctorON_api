<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class AppointmentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
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
                'date' => date('Y-m-d H:i:s', strtotime($request->data)), // Garante o formato correto
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
