<?php
namespace App\Validators\ValidatorsGeneral;

use App\Models\Usuario;
use App\Validators\Validator;

class UsuarioIsAdmin implements Validator{
    private Usuario $usuario;
    public function __construct(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function validate(): bool{
        return ($this->usuario!=null && $this->usuario->isAdmin());
    }

    public function message(): array{
        return [
            'error'=>'No tienes los permisos necesarios'
        ];
    }
}