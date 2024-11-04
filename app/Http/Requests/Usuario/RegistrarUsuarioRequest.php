<?php

namespace App\Http\Requests\Usuario;

use App\Enums\EstadoUsuarioEnum;
use App\Enums\RolEnum;
use App\Http\Requests\UbicacionRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrarUsuarioRequest extends FormRequest
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
            'estado' => strtoupper($this->input('estado')),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        $rules = [
            'nombre' => 'required|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'apellido' => 'required|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'correo' => 'required|email|unique:usuarios,correo',
            'fechaNacimiento' => 'before_or_equal:' . now()->subYears(18)->toDateString(),
            'clave' => 'required|min:8|max:20|regex:/^[a-zA-ZñÑ\-_0-9]+$/',
            'dni' => 'required|regex:/^[0-9]{7,12}$/|unique:usuarios,dni',
            'estado' => [
                'required',
                Rule::in(array_column(EstadoUsuarioEnum::cases(), 'value'))
            ],
            'rol' => [
                Rule::in(array_column(RolEnum::cases(), 'value'))
            ]
        ];

        if ($this->has('ubicacion')) {
            $ubicacion = new UbicacionRequest();
            $rules = array_merge($rules, $ubicacion->rules());
        }

        return $rules;
    }


    public function messages(): array
    {
        $ubicacionRequest = new UbicacionRequest();
        return array_merge(
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.min' => 'El nombre debe tener al menos :min caracteres',
                'nombre.max' => 'El nombre debe tener hasta :max caracteres',
                'nombre.regex' => 'El nombre solo acepta letras y espacios en blanco',
                'apellido.required' => 'El apellido es requerido',
                'apellido.min' => 'El apellido debe tener al menos :min caracteres',
                'apellido.max' => 'El apellido debe tener hasta :max caracteres',
                'apellido.regex' => 'El apellido solo acepta letras y espacios en blanco',
                'correo.required' => 'El correo es requerido',
                'correo.email' => 'El correo es inválido',
                'correo.unique' => 'El correo ya está en uso',
                'fechaNacimiento.required' => 'La fecha de nacimiento es requerida',
                'fechaNacimiento.date' => 'La fecha de nacimiento es inválida',
                'clave.required' => 'La clave es requerida',
                'clave.min' => 'La clave debe tener al menos :min caracteres',
                'clave.max' => 'La clave debe tener hasta :max caracteres',
                'clave.regex' => 'La clave puede tener letras, números y estos caracteres_ - _',
                'nacimiento.before_or_equal' => 'Debes ser mayor de 18 años.',
                'dni.required' => 'El DNI es requerido',
                'dni.regex' => 'DNI inválido',
                'dni.unique' => 'El DNI ya está en uso',
                'estado.required' => 'El estado es requerido',
                'estado.in' => 'El estado debe ser uno de los siguientes valores: ' . implode(', ', array_column(EstadoUsuarioEnum::cases(), 'value')),
                'rol.in' => 'El rol debe ser uno de los siguientes valores: ' . implode(', ', array_column(RolEnum::cases(), 'value')),
            ],
            $ubicacionRequest->messages(),
        );
    }
}