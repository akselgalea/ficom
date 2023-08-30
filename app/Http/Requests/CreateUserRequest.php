<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasAnyRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(FormRequest $req)
    {

        return [
          'name' => 'required|string',
          'email' => !empty($req->id) ? 'required|string|unique:users,email,'. $req->id . ',id' : 'required|string|unique:users,email',
          'roles' => 'sometimes|array',
          'password' => 'sometimes|string|min:6|max:22|confirmed',
          'roles.*' => 'string'
        ];
    }

    public function messages() {
      return [

      ];
    }

    public function attributes() {
      return [
        'name' => 'nombre',
        'password' => 'contraseña'
      ];
    }
}
