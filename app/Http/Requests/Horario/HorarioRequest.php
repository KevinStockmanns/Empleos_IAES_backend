<?php

namespace App\Http\Requests\Horario;

use Illuminate\Foundation\Http\FormRequest;

class HorarioRequest extends FormRequest
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
            'horarios'=>'required|array|min:1',
            'horarios.*.desde'=>'required|date_format:H:i',
            'horarios.*.hasta'=>'required|date_format:H:i',
            'horarios.*.dias'=>'regex:/^([0-6](,[0-6])*)?$/'
        ];
    }

    public function messages(){
        return [
            'horarios.required'=>'los horarios son requeridos',
            'horarios.array'=>'horarios debe ser un arreglo',
            'horarios.min'=>'los horarios debe tener al menos :min elemento',
            'horarios.*.desde.required' => 'la hora de inicio es requerida',
            'horarios.*.desde.date_format' => 'la hora de inicio debe ser en el formato HH:mm',

            'horarios.*.hasta.required' => 'la hora de finalizado es requerida',
            'horarios.*.hasta.date_format' => 'la hora de finalizado debe ser en el formato HH:mm',

            'horarios.*.dias.regex' => 'los días deben ser ingresados como números (0-6) (Domingo-Sábado) separados por ","',
        ];
    }
}
