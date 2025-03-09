<?php

namespace App\Http\Requests\Usuario;

use App\Validators\ValidatorHandler;
use App\Validators\ValidatorsGeneral\UsuarioHasPermission;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioDetalleRequest extends FormRequest
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
            'id'=> 'required|integer'
        ];
    }

    public function messages(){
        return [
            'id.integer'=>'El id debe ser un número entero',
        ];
    }
    protected function prepareForValidation(){
        $this->merge([
            'id' => $this->route('id')
        ]);
    }


    // public function withValidator($validator): void {
    //     $validator->after(function ($validator) {
    //         $idUsuario = $this->route('id');  
    //         $usuario = auth()->user();
    //         $handler = new ValidatorHandler();
    //         $handler->addValidator(new UsuarioHasPermission($idUsuario, $usuario));
    //         $handler->validate();
    //     });
    // }
}
