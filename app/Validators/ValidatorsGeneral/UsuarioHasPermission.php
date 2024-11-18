<?php
namespace App\Validators\ValidatorsGeneral;

use App\Models\Usuario;
use App\Validators\Validator;

class UsuarioHasPermission implements Validator{
    private $idUsuario;
    private $usuario;

    public function __construct($idUsuario, $usuario) {
        $this->idUsuario = $idUsuario;
        $this->usuario = $usuario;
    }

    public function validate(): bool{

        if (!$this->idUsuario || !$this->usuario || !$this->usuario instanceof Usuario) {
            return false;  
        }

        if(!$this->usuario->isAdmin() && $this->usuario->id!=$this->idUsuario){
            return false;
        }
        return true;
    }

    public function message(): array{
        return [
            'error'=>'No tienes los permisos necesarios'
        ];
    }



    
}