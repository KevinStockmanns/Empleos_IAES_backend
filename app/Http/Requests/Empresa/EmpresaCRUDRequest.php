<?php

namespace App\Http\Requests\Empresa;

use App\Http\Requests\Horario\HorarioRequest;
use App\Http\Requests\UbicacionRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaCRUDRequest extends FormRequest
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
        // $id ? Rule::unique('pesajes', 'nro_remito')->ignore($id) : 'unique:pesajes,nro_remito',
        $id = isset($this->id) ? $this->input('id') : null;
        $rules = [
            'id'=> 'nullable|exists:empresas,id',
            'nombre' => 'required|max:100|min:3|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'referente' => 'nullable|min:3|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'cuil_cuit' => [
                'required',
                'regex:/^\d{2}\-\d{7,10}\-\d$/',
                $id
                    ? Rule::unique('empresas', 'cuil_cuit')->ignore($id)
                    : 'unique:empresas,cuil_cuit'
            ],
            'idUsuairo' => 'nullable|numeric',
        ];
        if ($this->has('ubicacion')) {
            $ubicacion = new UbicacionRequest();
            $rules = array_merge($rules, $ubicacion->rules());
        }
        if ($this->has('horarios')) {
            $horario = new HorarioRequest();
            $rules = array_merge(
                $rules,
                $horario->rules()
            );
        }
        return $rules;
    }

    public function messages()
    {
        $ubicacion = new UbicacionRequest();
        $horario = new HorarioRequest();
        return array_merge(
            [
                'id.exists'=>'No existe una empresa con el id enviado.',
                'nombre.required' => 'el nombre es requerido',
                'nombre.max' => 'el nombre debe tener hasta :max caracteres',
                'nombre.min' => 'el nombre debe tener al menos :min caracteres',
                'nombre.regex' => 'el nombre acepta letras, números y espacios en blanco',

                'referente.min' => 'el referente debe tener al menos :min caracteres',
                'referente.max' => 'el referente puede tener hasta :max caracteres',
                'referente.regex' => 'el referente solo acepta letras y espacios en blanco',

                'cuil_cuit.regex' => 'el CUIL es inválido',
                'cuil_cuit.required'=>'El CUIL es requerido.',
                'cuil_cuit.unique'=>'El CUIL ya se encuentra en uso.',

                'idUsuario.numeric' => 'el id del usuario debe ser un calor númerico',
            ],
            $ubicacion->messages(),
            $horario->messages(),
        );
    }
}
