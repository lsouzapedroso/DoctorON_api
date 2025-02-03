<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_paciente' => Route::current()->parameter('id_paciente'),
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
            'id_paciente' => 'required|integer|exists:patients,id',
            'nome' => 'sometimes|string|max:255',
            'cpf' => 'prohibited',
            'celular' => 'sometimes|phone:BR',
        ];
    }

    public function messages(): array
    {
        return [
            'id_paciente.required' => 'O ID do paciente é obrigatório.',
            'id_paciente.integer' => 'O ID do paciente deve ser um número.',
            'id_paciente.exists' => 'O paciente informado não existe.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'celular.regex' => 'O celular deve estar no formato (XX) 9XXXX-XXXX ou (XX)9XXXXXXXX.',
            'celular.phone' => 'O número de celular não é válido.',
            'cpf.prohibited' => 'O CPF não pode ser alterado.',
        ];
    }
}
