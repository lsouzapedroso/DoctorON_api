<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ShowMadicPatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            '$id_medico' => Route::current()->parameter('$id_medico'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '$id_medico' => 'required|integer|exists:medic,id',
        ];
    }

    public function messages(): array
    {
        return [
            '$id_medico.required' => 'O campo medico é obrigatorio',
            '$id_medico.integer' => 'O Id do medico deve ser um numero inteiro',
            '$id_medico.exit' => 'O medico informado não exite',
        ];
    }
}
