<?php

namespace App\Http\Requests\Usuario;

use App\Enums\EstadoCivilEnum;
use App\Enums\EstadoUsuarioEnum;
use App\Enums\GeneroEnum;
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

        $usuario = null;
        if(auth()->check()){
            $usuario = auth()->user();
        }
        $rules = [
            'nombre' => 'required|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'apellido' => 'required|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'correo' => 'required|email|unique:usuarios,correo',
            'fechaNacimiento' => 'before_or_equal:' . now()->subYears(18)->toDateString(),
            'clave' => 'required|min:8|max:20|regex:/^[a-zA-ZñÑ\-_0-9]+$/',
            'dni' => 'required|regex:/^[0-9]{8,12}$/|unique:usuarios,dni',
            'rol'=> ['required', Rule::in([RolEnum::ALUMNO->value, RolEnum::EGRESADO->value])],
            'estado_civil'=> ['required', Rule::in(array_column(EstadoCivilEnum::cases(), 'value'))],
            'genero'=>['required', Rule::in(array_column(GeneroEnum::cases(), 'value'))]
        ];

        if($usuario!=null && $usuario->isAdmin()){
            $rules['clave'] = 'min:8|max:20|regex:/^[a-zA-ZñÑ\-_0-9]+$/';
            $rules['estado'] = ["required", Rule::in(array_column(EstadoUsuarioEnum::cases(), 'value'))];
            $rules['rol'] = [
                'required',
                Rule::in(array_column(RolEnum::cases(), 'value'))
            ];
        }

        if ($this->has('ubicacion')) {
            $ubicacion = new UbicacionRequest();
            $rules = array_merge($rules, $ubicacion->rules());
        }

        return $rules;
    }


    public function messages(): array
    {
        $ubicacionRequest = new UbicacionRequest();
        $usuario = auth()->user();
        
        if ($usuario != null && $usuario->isAdmin()) {
            $rolMessage = 'El rol debe ser uno de los siguientes valores: ' . implode(', ', array_column(RolEnum::cases(), 'value'));
        } else {
            $rolMessage = 'El rol debe ser uno de los siguientes valores: ALUMNO, EGRESADO';
        }
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
                'clave.regex' => 'La clave puede tener letras, números y estos caracteres: - _',
                'fechaNacimiento.before_or_equal' => 'Debes ser mayor de 18 años.',
                'dni.required' => 'El DNI es requerido',
                'dni.regex' => 'DNI inválido',
                'dni.unique' => 'El DNI ya está en uso',
                'estado.required' => 'El estado es requerido',
                'estado.in' => 'El estado debe ser uno de los siguientes valores: ' . implode(', ', array_column(EstadoUsuarioEnum::cases(), 'value')),
                'rol.required'=>'El rol es requerido',
                'rol.in' => $rolMessage,
                'estado_civil.required'=> 'El estado civil es requerido',
                'estado_civil.in'=>'Las opciones válidas para el estado civil son: '. implode(', ', array_column(EstadoCivilEnum::cases(), 'value')),
                'genero.required'=>'El género del usuario es requerido.',
                'genero.in'=>'Las opciones válidas para el género son: ' . implode(', ', array_column(GeneroEnum::cases(), 'value')),
            ],
            $ubicacionRequest->messages(),
        );
    }
}
