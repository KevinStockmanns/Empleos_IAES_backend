<?php

namespace App\Http\Requests\Usuario;

use App\Enums\EstadoUsuarioEnum;
use App\Enums\RolEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioActualizarRequest extends FormRequest
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
            'nombre' => 'nullable|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'apellido' => 'nullable|min:3|max:100|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'correo' => 'nullable|email|unique:usuarios,correo',
            'fechaNacimiento' => 'before_or_equal:' . now()->subYears(18)->toDateString(),
            'dni' => 'nullable|regex:/^[0-9]{7,12}$/|unique:usuarios,dni',
            'rol' => [
                Rule::in(array_column(RolEnum::cases(), 'value'))
            ]
        ];
    }
}
