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
            'horario.desde'=>'required|date_format:H:i',
            'horario.hasta'=>'required|date_format:H:i',
            'horario.dias'=>'regex:/^([0-6](,[0-6])*)?$/'
        ];
    }

    public function messages(){
        return [
            'horario.desde.required' => 'la hora de inicio es requerida',
            'horario.desde.date_format' => 'la hora de inicio debe ser en el formato HH:mm',

            'horario.hasta.required' => 'la hora de finalizado es requerida',
            'horario.hasta.date_format' => 'la hora de finalizado debe ser en el formato HH:mm',

            'horario.dias.regex' => 'los días deben ser ingresados como números (0-6) (Domingo-Sábado) separados por ","',
        ];
    }
}
