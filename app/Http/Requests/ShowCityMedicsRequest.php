<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ShowCityMedicsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id_cidade' => Route::current()->parameter('id_cidade'),
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
            'id_cidade' => 'required|integer|exists:cities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_cidade.required' => 'O ID da cidade é obrigatório.',
            'id_cidade.integer' => 'O ID da cidade deve ser um número inteiro.',
            'id_cidade.exists' => 'A cidade informada não existe.',
        ];
    }
}
