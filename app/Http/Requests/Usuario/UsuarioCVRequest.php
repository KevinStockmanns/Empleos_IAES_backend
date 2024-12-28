<?php

namespace App\Http\Requests\Usuario;

use App\Rules\OwnerOrAdmin;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioCVRequest extends FormRequest
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
            'cv'=>'required|file|mimes:pdf|extensions:pdf',
            'id'=>[
                'required', 'integer', new OwnerOrAdmin
            ]
        ];
    }


    public function messages(){
        return [
            'cv.required'=>'El cv es requerido',
            'cv.file'=>'El cv es inválido.',
            'cv.mimes'=>'El cv debe estar en formato pdf.',
            'cv.extensions'=>'El cv debe estar en formato pdf.',

            'id.required'=>'El id es requerido.',
            'id.integer'=>'El id debe ser un número entero.',
        ];
    }


    public function prepareForValidation(){
        $this->merge([
            'id'=> $this->route('id'),
        ]);
    }
}
