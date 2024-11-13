<?php

namespace App\Http\Requests\Usuario;

use App\Enums\DisponibilidadEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioPerfilProfesionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function prepareForValidation(){
        $this->merge([
            'perfilProfesional.disponibilidad' => strtoupper($this->input('perfilProfesional.disponibilidad')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'perfilProfesional.cargo'=> 'required|min:3|max:40|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\.]+$/',
            'perfilProfesional.cartaPresentacion'=>'string|min:100|max:3000|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,;:\'"\s\-()!?\n]+$/',
            'perfilProfesional.disponibilidad'=>[
                'required',
                Rule::in(array_column(DisponibilidadEnum::cases(), 'value'))
            ],
            'perfilProfesional.disponibilidadMudanza'=> 'required|boolean'
        ];
    }

    public function messages(){
        return [
            'perfilProfesional.cargo.required'=>'el cargo es requerido',
            'perfilProfesional.cargo.min'=>'el cargo debe tener al menos :min caracteres',
            'perfilProfesional.cargo.max'=>'el cargo debe tener hasta :max caracteres',
            'perfilProfesional.cargo.regex'=>'el cargo solo acepta letras y espacios en blanco',

            'perfilProfesional.cartaPresentacion.string'=>'la carta de presentación debe ser texto',
            'perfilProfesional.cartaPresentacion.min'=>'ña carta de presentación tener al menos :min caracteres',
            'perfilProfesional.cartaPresentacion.max'=>'la carta de presentación puede tener hasta :max caractres',
            'perfilProfesional.cartaPresentacion.regex'=>'la carta de presentación tiene caracteres inválidos',

            'perfilProfesional.disponibilidad.required'=>'la disponibilidad es requerida',
            'perfilProfesional.disponibilidad.in'=>'la disponibilidad solo acepta los siguientes valores: '. implode(', ', array_column(DisponibilidadEnum::cases(), 'value')),

            'perfilProfesional.disponibilidadMudanza.required'=>'la disponibilidad de mudanza es requerida',
            'perfilProfesional.disponibilidadMudanza.boolean'=>'la disponibilidad de mudanza debe ser del tipo booleano'
        ];
    }
}
