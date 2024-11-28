<?php

namespace App\Rules;

use App\Exceptions\CustomException;
use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnerOrAdmin implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $usuario = Usuario::find($value);
        if(!$usuario){
            //lanzar algun error
            throw new CustomException('El usuario no fue encontrado en la base de datos.', 404);
        }
        $usuarioAuth = auth()->user();
        if(!$usuarioAuth->isAdmin() && $usuario->id != $usuarioAuth->id){
            throw new CustomException('No tienes los permisos necesarios', 403);
        }
        request()->attributes->set('usuarioValidado', $usuario);
    }
}
