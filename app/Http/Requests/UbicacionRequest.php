<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UbicacionRequest extends FormRequest
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
            'ubicacion.pais' => 'required|string|max:100',
            'ubicacion.provincia' => 'required|string|max:100',
            'ubicacion.localidad' => 'required|string|max:100',
            'ubicacion.barrio'=>'required|string|max:255',
            'ubicacion.direccion' => 'required|string|max:255',
            'ubicacion.numero' => 'numeric',
            'ubicacion.piso' => 'numeric',
            'ubicacion.accion'=> 'nullable|in:AGREGAR,ELIMINAR,ACTUALIZAR'
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ubicacion.pais.required' => 'El país es requerido.',
            'ubicacion.pais.string' => 'El país debe ser una cadena de texto.',
            'ubicacion.pais.max' => 'El país no puede tener más de 100 caracteres.',
            
            'ubicacion.provincia.required' => 'La provincia es requerida.',
            'ubicacion.provincia.string' => 'La provincia debe ser una cadena de texto.',
            'ubicacion.provincia.max' => 'La provincia no puede tener más de 100 caracteres.',
            
            'ubicacion.localidad.required' => 'La localidad es requerida.',
            'ubicacion.localidad.string' => 'La localidad debe ser una cadena de texto.',
            'ubicacion.localidad.max' => 'La localidad no puede tener más de 100 caracteres.',
            
            'ubicacion.barrio.required' => 'El barrio es requerido.',
            'ubicacion.barrio.string' => 'El barrio debe ser una cadena de texto.',
            'ubicacion.barrio.max' => 'El barrio no puede tener más de 255 caracteres.',
            
            'ubicacion.direccion.required' => 'La dirección es requerida.',
            'ubicacion.direccion.string' => 'La dirección debe ser una cadena de texto.',
            'ubicacion.direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
            
            'ubicacion.numero.numeric' => 'El número debe ser un valor numérico.',
            'ubicacion.piso.numeric' => 'El piso debe ser un valor numérico.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ubicacion' => array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $this->input('ubicacion', [])),
        ]);
    }
}
