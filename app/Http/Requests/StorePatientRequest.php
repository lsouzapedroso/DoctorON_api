<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'cpf' => 'required|cpf|unique:patients,cpf',
            'celular' => 'required|phone:BR',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.string' => 'O campo CPF deve ser um texto.',
            'cpf.cpf' => 'O campo CPF deve ser um CPF válido.',
            'cpf.unique' => 'O CPF informado já está cadastrado.',
            'celular.required' => 'O campo celular é obrigatório.',
            'celular.regex' => 'O celular deve estar no formato (XX) 9XXXX-XXXX ou (XX)9XXXXXXXX.',
            'celular.phone' => 'O número de celular não é válido.',
        ];
    }
}
