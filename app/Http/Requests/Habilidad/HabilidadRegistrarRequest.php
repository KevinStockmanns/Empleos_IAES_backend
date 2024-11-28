<?php

namespace App\Http\Requests\Habilidad;

use App\Enums\HabilidadEnum;
use App\Validators\ValidatorHandler;
use App\Validators\ValidatorsGeneral\UsuarioHasPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HabilidadRegistrarRequest extends FormRequest
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
            'id' => 'required|integer',
            'habilidades' => 'required|array|min:1',
            'habilidades.*.nombre' => 'required|string|min:3|max:100',
            'habilidades.*.tipo' => [
                'required',
                Rule::in(array_column(HabilidadEnum::cases(), 'value'))
            ],
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'El id del usuario es requerido',
            'id.integer' => 'El id del usuario debe ser un número entero',


            'habilidades.required' => 'Las habilidades son requeridas',
            'habilidades.array' => 'Las habilidades deben ser un arreglo',
            'habilidades.min' => 'Las habilidades deben tener al menos :min dato',

            'habilidades.*.nombre.required' => 'El nombre de la habilidad es requerido',
            'habilidades.*.nombre.string' => 'El nombre de la habilidad debe ser texto',
            'habilidades.*.nombre.min' => 'El nombre de la habilidad debe tener al menos :min caracteres',
            'habilidades.*.nombre.max' => 'El nombre de la habilidad debe tener hasta :max caracteres',

            'habilidades.*.tipo.required' => 'El tipo de habilidad es requerido',
            'habilidades.*.tipo.in' => 'El tipo de habilidad solo acepta: ' . implode(', ', array_column(HabilidadEnum::cases(), 'value')),
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
            'habilidad.tipo' => strtoupper($this->input('habilidad.tipo')),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $idUsuario = $this->route('id');
            $usuario = auth()->user();

            $handler = new ValidatorHandler();
            $handler->addValidator(new UsuarioHasPermission($idUsuario, $usuario));
            $handler->validate();
        });
    }
}
