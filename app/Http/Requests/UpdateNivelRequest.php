<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNivelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasAnyRole('admin', 'contabilidad');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'matricula' => 'required|integer',
            'arancel' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe ser un número',
            'max' => 'El campo :attribute debe ser menor a :max'
        ];
    }

    public function attributes(): array
    {
        return [
            'matricula' => 'matrícula'
        ];
    }
}
