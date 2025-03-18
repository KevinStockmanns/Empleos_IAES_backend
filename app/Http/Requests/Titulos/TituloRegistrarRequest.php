<?php

namespace App\Http\Requests\Titulos;

use App\Enums\AccionCrudEnum;
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
            'titulos'=>'required|array|min:1',

            'titulos.*.id'=>'integer',

            'titulos.*.accion'=>['required', Rule::in(array_column(AccionCrudEnum::cases(), 'value'))],
            'titulos.*.nombre'=>'max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s\-0-9]+$/',
            'titulos.*.institucion'=>'max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s\-0-9]+$/',
            'titulos.*.alias'=>'nullable|max:12',

            'titulos.*.fechaInicio'=> 'date|before:today',
            'titulos.*.fechaFin'=> 'nullable|date',
            'titulos.*.promedio'=> 'nullable|numeric|min:0|max:10',
            'titulos.*.tipo'=>[
                // 'required',
                Rule::in(array_column(TituloTipoEnum::cases(), 'value'))
            ],
            'titulos.*.descripcion'=>'nullable|string|min:50|max:500'
        ];
    }

    public function messages(){
        return [
            'id.required' => 'El ID del usuario es obligatorio.',
            'id.integer' => 'El ID del usuario debe ser un número entero.',

            'titulos.*.id.integer'=>'El id del titulo debe ser un número entero.',

            'titulos.*.accion.required'=>'La acción es requerida',
            'titulos.*.accion.in'=>'La acción solo acepta: ' .implode(', ', array_column(AccionCrudEnum::cases(), 'value')) . '.',

            'titulos.*.nombre.required'=>'El nombre del titulo es requerido.',
            'titulos.*.nombre.max'=>'El nombre del titulo puede tener hasta :max caracteres.',
            'titulos.*.nombre.regex'=>'El nombre del titulo acepta letras y números.',

            'titulos.*.institucion.required'=> 'El nombre de la institución es requerido.',
            'titulos.*.institucion.max'=> 'El nombre de la institución puede tener hasta :max caracteres.',
            'titulos.*.institucion.regex'=> 'El nombre de la institución acepta letras y números.',

            'titulos.*.alias.max'=>'El alias puede tener hasta :max caracteres',

            'titulos.required'=> 'Los titulos son requeridos',
            'titulos.array'=> 'Los titulos deben ser arreglos',
            'titulos.min'=> 'Los titulos deben tener al menos :min dato(s)',

            'titulos.*.fechaInicio.required'=> 'La fecha de inicio es requerida.',
            'titulos.*.fechaInicio.date'=> 'La fecha de inicio es inválida.',
            'titulos.*.fechaInicio.before'=> 'La fecha de inicio no puede ser posterior a la fecha.',

            'titulos.*.fechaFin.date'=> 'La fecha de fin es inválida.',

            'titulos.*.promedio.numeric'=> 'El promedio debe ser un número.',
            'titulos.*.promedio.min'=> 'El promedio debe ser como minimo :min.',
            'titulos.*.promedio.max'=> 'El promedio puede valer hasta :max.',
            'titulos.*.promedio.regex'=> 'El promedio debe estar separado por un punto.',

            'titulos.*.tipo.required'=> 'El "tipo" es requerido.',
            'titulos.*.tipo.in'=> 'El dato "tipo" solo acepta: ' . implode(', ', array_column(TituloTipoEnum::cases(), 'value')) . '.',
            
            'titulos.*.descripcion.string'=>'La descripción debe ser texto.',
            'titulos.*.descripcion.min'=>'La descripción debe tener :min caracteres.',
            'titulos.*.descripcion.max'=>'La descripción puede tener hasta :max caracteres.',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'id'=> $this->route('id'),
        ]);
    }

    // public function withValidator($validator){
    //     $validator->after(function($validator){
    //         $handler = new ValidatorHandler();
    //         $idUsuario = $this->route('id');
    //         $usuario = auth()->user();
    //         $handler->addValidator(new ValidarTituloSinRepetir($this->validated()));

    //         $handler->validate();
    //     });
    // }
}
