<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioLoginRequest extends FormRequest
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
            'username' => 'required',
            'clave' => 'required|min:8|max:20|regex:/^[a-zA-ZñÑ\-_0-9]+$/'
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'El nombre de usuario es requerido',
            'clave.required' => 'La clave es requerida',
            'clave.min' => 'La clave debe tener al menos :min caracteres',
            'clave.max' => 'La clave debe tener hasta :max caracteres',
            'clave.regex' => 'La clave puede tener letras, números y estos caracteres_ - _',
        ];
    }
}
