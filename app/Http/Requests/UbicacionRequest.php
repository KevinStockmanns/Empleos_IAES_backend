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
        if ($this->has('ubicacion')) {
            return [
                'ubicacion.pais' => 'required|string|max:100',
                'ubicacion.provincia' => 'required|string|max:100',
                'ubicacion.localidad' => 'required|string|max:100',
                'ubicacion.direccion' => 'required|string|max:255',
                'ubicacion.numero' => 'required|numeric',
            ];
        }

        return [];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ubicacion.pais.required' => 'El país es requerido',
            'ubicacion.provincia.required' => 'La provincia es requerida',
            'ubicacion.localidad.required' => 'La localidad es requerida',
            'ubicacion.direccion.required' => 'La dirección es requerida',
            'ubicacion.numero.required' => 'El número es requerido',
            'ubicacion.numero.numeric' => 'Debe ser un número entero',
        ];
    }
}
