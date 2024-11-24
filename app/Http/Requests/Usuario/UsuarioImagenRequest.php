<?php

namespace App\Http\Requests\Usuario;

use App\Validators\ValidatorHandler;
use App\Validators\ValidatorsGeneral\UsuarioHasPermission;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioImagenRequest extends FormRequest
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
            'imagen'=>'required|image|mimes:jpeg,png,jpg,webp|extensions:jpeg,jpg,png,webp',
            'id'=>'required|integer'
        ];
    }


    public function messages(){
        return [
            'imagen.required'=> 'La imágen es requerida',
            'imagen.image'=>'El archivo seleccionado debe ser una imagen (JPEG, PNG, JPG, o WEBP)',
            'imagen.mimes' => 'El archivo seleccionado no es un formato válido. Por favor, seleccione un archivo JPEG, PNG, JPG o WEBP.',
            'imagen.extensions' => 'El archivo seleccionado no es un formato válido. Por favor, seleccione un archivo JPEG, PNG, JPG o WEBP.',

            'id.required'=>'El id del usuario es requerido',
            'id.integer'=>'El id debe ser un número entero',
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
            $handler->addValidator(new UsuarioHasPermission($idUsuario, $usuario));

            $errors=$handler->validate();
            foreach ($errors as $key => $message) {
                $validator->errors()->add($key, $message);
            }
        });
    }


}
