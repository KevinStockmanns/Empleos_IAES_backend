<?php
namespace App\Validators\ValidatorsGeneral;

use App\Exceptions\CustomException;
use App\Models\Usuario;
use App\Validators\Validator;

class UsuarioIsAdmin implements Validator{
    private Usuario $usuario;
    public function __construct(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function validate(): void{
        if ($this->usuario==null || !$this->usuario->isAdmin()){
            throw new CustomException('No tienes los permisos necesarios.', 403);
        }
    }
}