<?php

namespace App\Http\Requests\Titulos;

use App\Enums\TituloTipoEnum;
use App\Rules\OwnerOrAdmin;
use App\Validators\Titulo\Registrar\ValidarTituloSinRepetir;
use App\Validators\ValidatorHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TituloRegistrarRequest extends FormRequest
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
            'id'=>['required', 'integer', new OwnerOrAdmin],
            'titulo.nombre'=>'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s\-0-9]+$/',
            'titulo.institucion'=>'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s\-0-9]+$/',
            'titulo.alias'=>'nullable|max:12',

            // 'titulo.titulos'=> 'required|array|min:1',
            'titulo.fechaInicio'=> 'required|date|before:today',
            'titulo.fechaFin'=> 'nullable|date',
            'titulo.promedio'=> 'nullable|numeric|min:0|max:10|regex:/^\d{1,2}(\d{1,2})?$/',
            'titulo.tipo'=>[
                'required',
                Rule::in(array_column(TituloTipoEnum::cases(), 'value'))
            ],
            'titulo.descripcion'=>'nullable|string|min:50|max:400'
        ];
    }

    public function messages(){
        return [
            'id.required' => 'El ID del usuario es obligatorio.',
            'id.integer' => 'El ID del usuario debe ser un número entero.',

            'titulo.nombre.required'=>'El nombre del titulo es requerido.',
            'titulo.nombre.max'=>'El nombre del titulo puede tener hasta :max caracteres.',
            'titulo.nombre.regex'=>'El nombre del titulo acepta letras y números.',

            'titulo.institucion.required'=> 'El nombre de la institución es requerido.',
            'titulo.institucion.max'=> 'El nombre de la institución puede tener hasta :max caracteres.',
            'titulo.institucion.regex'=> 'El nombre de la institución acepta letras y números.',

            'titulo.alias.max'=>'El alias puede tener hasta :max caracteres',

            // 'titulo.required'=> 'Los titulos son requeridos',
            // 'titulo.array'=> 'Los titulos deben ser arreglos',
            // 'titulo.min'=> 'Los titulos deben tener al menos :min dato(s)',

            'titulo.fechaInicio.required'=> 'La fecha de inicio es requerida.',
            'titulo.fechaInicio.date'=> 'La fecha de inicio es inválida.',
            'titulo.fechaInicio.before'=> 'La fecha de inicio no puede ser posterior a la fecha.',

            'titulo.fechaFin.date'=> 'La fecha de fin es inválida.',

            'titulo.promedio.numeric'=> 'El promedio debe ser un número.',
            'titulo.promedio.min'=> 'El promedio debe ser como minimo :min.',
            'titulo.promedio.max'=> 'El promedio puede valer hasta :max.',
            'titulo.promedio.regex'=> 'El promedio debe estar separado por un punto.',

            'titulo.tipo.required'=> 'El "tipo" es requerido.',
            'titulo.tipo.in'=> 'El dato "tipo" solo acepta: ' . implode(', ', array_column(TituloTipoEnum::cases(), 'value')) . '.',
            
            'titulo.descripcion.string'=>'La descripción debe ser texto.',
            'titulo.descripcion.min'=>'La descripción debe tener :min caracteres.',
            'titulo.descripcion.max'=>'La descripción puede tener hasta :max caracteres.',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'id'=> $this->route('id'),
        ]);
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            $handler = new ValidatorHandler();
            $idUsuario = $this->route('id');
            $usuario = auth()->user();
            $handler->addValidator(new ValidarTituloSinRepetir($this->input('titulo.nombre'), $this->input('titulo.institucion')));

            $handler->validate();
        });
    }
}
