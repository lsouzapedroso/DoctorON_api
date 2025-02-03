<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medico_id' => 'required|exists:medics,id',
            'paciente_id' => 'required|exists:patients,id',
            'data' => 'required|date_format:Y-m-d H:i:s|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'medico_id.required' => 'O campo médico é obrigatório.',
            'medico_id.exists' => 'O médico informado não existe.',
            'paciente_id.required' => 'O campo paciente é obrigatório.',
            'paciente_id.exists' => 'O paciente informado não existe.',
            'data.required' => 'A data da consulta é obrigatória.',
            'data.date' => 'A data da consulta deve ser uma data válida.',
            'data.after_or_equal' => 'A data da consulta não pode ser anterior a hoje.',
        ];
    }
}
