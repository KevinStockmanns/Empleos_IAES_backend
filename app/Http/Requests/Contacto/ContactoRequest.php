<?php

namespace App\Http\Requests\Contacto;

use App\Validators\ValidatorHandler;
use App\Validators\ValidatorsGeneral\UsuarioHasPermission;
use Illuminate\Foundation\Http\FormRequest;

class ContactoRequest extends FormRequest
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
            'contacto.telefono'=> 'required|regex:/^\+?(\d{1,3})?[-\s]?(\d{2,4}[-\s]?){1,3}\d{4,}$/',
            'contacto.telefonoFijo'=>'nullable|regex:/^(0\d{1,2})?[-\s]?\d{4}[-\s]?\d{4}$/',
            'contacto.linkedin'=> 'nullable|url',
            'contacto.paginaWeb'=>'nullable|url'
        ];
    }

    public function messages(){
        return [
            'contacto.telefono.required'=> 'El telefono es requerido',
            'contacto.telefono.regex'=>'El formato del telefono debe ser +54 0000 000000',
            'contacto.telefonoFijo.regex'=> 'El formato del telefono fijo debe ser 011 1234 5678',
            'contacto.linkedin.url'=> 'El enlace de linkedin no es válido',
            'contacto.paginaWeb.url'=> 'La página web no es válida'
        ];
    }



    public function withValidator($validator): void {
        $validator->after(function ($validator) {
            $idUsuario = $this->route('id');  
            $usuario = auth()->user();
            $handler = new ValidatorHandler();
            $handler->addValidator(new UsuarioHasPermission($idUsuario, $usuario));
            $errors = $handler->validate();
            
            foreach ($errors as $key => $message) {
                $validator->errors()->add($key, $message);
            }
        });
    }

    
}
