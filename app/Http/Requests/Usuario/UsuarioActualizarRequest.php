<?php

namespace App\Http\Requests\Usuario;

use App\Enums\EstadoCivilEnum;
use App\Enums\EstadoUsuarioEnum;
use App\Enums\GeneroEnum;
use App\Enums\RolEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioActualizarRequest extends FormRequest
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
            'nombre' => 'nullable|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'apellido' => 'nullable|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'correo' => 'nullable|email|unique:usuarios,correo',
            'fechaNacimiento' => 'before_or_equal:' . now()->subYears(18)->toDateString(),
            'dni' => 'nullable|regex:/^[0-9]{7,12}$/|unique:usuarios,dni',
            'estado_civil' => ['nullable', Rule::in(array_column(EstadoCivilEnum::cases(), 'value'))],
            'genero' => ['nullable', Rule::in(array_column(GeneroEnum::cases(), 'value'))],
            'rol' => [
                Rule::in(array_column(RolEnum::cases(), 'value'))
            ]
        ];
    }


    public function messages(): array{
        return [
            'nombre.min' => 'El nombre debe tener al menos :min caracteres.',
            'nombre.max' => 'El nombre no puede superar los :max caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.min' => 'El apellido debe tener al menos :min caracteres.',
            'apellido.max' => 'El apellido no puede superar los :max caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'correo.email' => 'El correo ingresado no es válido.',
            'correo.unique' => 'El correo ingresado ya está en uso.',
            'fechaNacimiento.before_or_equal' => 'Debes ser mayor de 18 años.',
            'dni.regex' => 'El DNI debe contener entre 7 y 12 dígitos numéricos.',
            'dni.unique' => 'El DNI ingresado ya está en uso.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'estado_civil.in' => 'El estado civil debe ser uno de los siguientes valores: ' . implode(', ', array_column(EstadoCivilEnum::cases(), 'value')),
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser uno de los siguientes valores: ' . implode(', ', array_column(GeneroEnum::cases(), 'value')),
            'rol.in' => 'El rol debe ser uno de los siguientes valores: ' . implode(', ', array_column(RolEnum::cases(), 'value')),
        ];
    }

}
