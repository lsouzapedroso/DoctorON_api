<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ShowMedicPatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare validation before rules are applied.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'id_medico' => Route::current()->parameter('id_medico'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_medico' => 'required|integer|exists:medics,id',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'id_medico.required' => 'O campo médico é obrigatório.',
            'id_medico.integer' => 'O ID do médico deve ser um número inteiro.',
            'id_medico.exists' => 'O médico informado não existe.',
        ];
    }
}
