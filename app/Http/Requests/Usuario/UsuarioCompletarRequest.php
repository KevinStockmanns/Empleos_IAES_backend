<?php

namespace App\Http\Requests\Usuario;

use App\Http\Requests\UbicacionRequest;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioCompletarRequest extends FormRequest
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
        $ubicacionRequest = new UbicacionRequest();

        return array_merge(
            [
                'fechaNacimiento'=>'before_or_equal:' . now()->subYears(18)->toDateString()
            ],
            $ubicacionRequest->rules()
        );
    }


    public function messages(){
        $ubicacionRequest = new UbicacionRequest();

        return array_merge(
            [
                'fechaNacimiento.before_or_equal'=> "Debes tener al menos 18 años"
            ],
            $ubicacionRequest->messages()
        );
    }
}
