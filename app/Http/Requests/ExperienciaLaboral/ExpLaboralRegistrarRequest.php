<?php

namespace App\Http\Requests\ExperienciaLaboral;

use App\Enums\AccionCrudEnum;
use App\Rules\OwnerOrAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpLaboralRegistrarRequest extends FormRequest
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
        $rules= [
            'id'=>['required', 'integer', new OwnerOrAdmin],
            'experienciaLaboral'=>'required|array|min:1',
            'experienciaLaboral.*.puesto'=>'string|min:3|max:50|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'experienciaLaboral.*.empresa'=>'string|min:3|max:50',
            'experienciaLaboral.*.idEmpresa'=>'nullable|integer',
            'experienciaLaboral.*.descripcion' => 'nullable|string|min:15|max:500',
            'experienciaLaboral.*.fechaInicio'=>'date|before:today',
            'experienciaLaboral.*.fechaTerminacion'=>'nullable|date|before:today',
            'experienciaLaboral.*.accion'=>['required', Rule::in(array_column(AccionCrudEnum::cases(), 'value'))],
            'experienciaLaboral.*.id'=>['nullable', 'integer'],
            
        ];

        return $rules;
    }

    public function prepareForValidation(){
        $this->merge([
            'id'=> $this->route('id'),
        ]);
    }


    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $expLaboralesDto = $this->input('experienciaLaboral', []);

        // Asegúrate de que experienciaLaboral sea un array
        if (!is_array($expLaboralesDto)) {
            $validator->errors()->add(
                'experienciaLaboral',
                'El campo experienciaLaboral debe ser un arreglo.'
            );
            return;
        }

        foreach ($expLaboralesDto as $index => $item) {
            if (empty($item['idEmpresa']) && empty($item['empresa']) && $item['accion']== AccionCrudEnum::AGREGAR->value) {
                $validator->errors()->add(
                    "experienciaLaboral.$index.empresa",
                    'El campo empresa es obligatorio si no se proporciona un idEmpresa.'
                );
            }

            if ($item['accion'] != AccionCrudEnum::AGREGAR->value && empty($item['id'])) {
                $validator->errors()->add(
                    "experienciaLaboral.$index.id",
                    'El id de la experiencia laboral es requerido.'
                );
            }

            if ($item['accion'] == AccionCrudEnum::AGREGAR->value) {
                if (empty($item['puesto'])) {
                    $validator->errors()->add(
                        "experienciaLaboral.$index.puesto",
                        'El puesto es requerido.'
                    );
                }
                if (empty($item['fechaInicio'])) {
                    $validator->errors()->add(
                        "experienciaLaboral.$index.fechaInicio",
                        'La fecha de inicio es requerida.'
                    );
                }
            }

            if($item['accion']==AccionCrudEnum::ACTUALIZAR->value){
                if(
                    !isset($item['puesto'])
                    && !isset($item['empresa'])
                    && !isset($item['fechaInicio'])
                    && !isset($item['fechaTerminacion'])
                    && !isset($item['descripcion'])
                    && !isset($item['idEmpresa'])
                ){
                    $validator->errors()->add(
                        'experienciaLaboral.'.$index,
                        'Para actualizar es requerido al menos un dato'
                    );
                }
            }
        }
    });
}



    public function messages(){
        return [
            'id.required'=>'El id del usuario es requerido',
            'id.integer'=>'El id debe ser un número entero',

            'experienciaLaboral.required'=>'La experiencia laboral es requerida.',
            'experienciaLaboral.array'=>'La experiencia laboral es debe ser del tipo arreglo.',
            'experienciaLaboral.min'=>'La experiencia laboral debe tener al menos :min objeto',

            'experienciaLaboral.*.puesto.required'=>'El puesto es requerido',
            'experienciaLaboral.*.puesto.string'=>'El puesto debe ser texto válido.',
            'experienciaLaboral.*.puesto.min'=>'El puesto debe tener al menos :min caracteres.',
            'experienciaLaboral.*.puesto.max'=>'El puesto puede tener hasta :max caracteres.',
            'experienciaLaboral.*.puesto.regex'=>'El puesto acepta letras y números.',

            'experienciaLaboral.*.empresa.string'=>'El dato empresa debe ser del tipo texto.',
            'experienciaLaboral.*.min'=>'La empresa debe tener al menos :min caracteres.',
            'experienciaLaboral.*.empresa.max'=>'La empresa puede tener hasta :max caracteres.',
            'experienciaLaboral.*.empresa.regex'=> 'La empresa solo acepta letras y números.',

            'experienciaLaboral.*.idEmpresa.integer'=>'El id de la empresa debe ser un número entero.',

            'experienciaLaboral.*.descripcion.string'=>'El campo descripción debe ser del tipo dato texto',
            'experienciaLaboral.*.descripcion.min'=>'La descripción debe tener al menos :min caracteres',
            'experienciaLaboral.*.descripcion.max'=>'La descripción puede tener hasta :max caracteres',
            'experienciaLaboral.*.descripcion.regex'=>'La descripción puede tener hasta :max caracteres',

            'experienciaLaboral.*.fechaInicio.required'=>'La fecha de inicio es requerida.',
            'experienciaLaboral.*.fechaInicio.date'=>'La fecha de inicio es inválida.',
            'experienciaLaboral.*.fechaInicio.before'=>'La fecha de inicio debe ser previa a la actual.',

            'experienciaLaboral.*.fechaTerminacion.date'=>'La fecha de terminación es inválida',
            'experienciaLaboral.*.fechaTerminacion.before'=>'La fecha de terminación debe ser previa a la actual',


            'experienciaLaboral.*.accion.required'=>'La acción es requerida',
            'experienciaLaboral.*.accion.in'=>'La acción solo acepta: '. implode(', ', array_column(AccionCrudEnum::cases(), 'value')),


            'experienciaLaboral.*.id.integer'=>'El id de la experiencia laboral debe ser un número entero',

            
        ];
    }
}
