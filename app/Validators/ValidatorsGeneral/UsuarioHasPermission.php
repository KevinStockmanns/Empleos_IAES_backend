<?php
namespace App\Validators\ValidatorsGeneral;

use App\Exceptions\CustomException;
use App\Models\Usuario;
use App\Validators\Validator;

class UsuarioHasPermission implements Validator{
    private $idUsuario;
    private $usuario;

    public function __construct($idUsuario, $usuario) {
        $this->idUsuario = $idUsuario;
        $this->usuario = $usuario;
    }

    public function validate(): void{

        if (!$this->idUsuario || !$this->usuario || !$this->usuario instanceof Usuario) {
            throw new CustomException('Ocurrio un error al autenticar el usaurio',500);
        }

        if(!$this->usuario->isAdmin() && $this->usuario->id!=$this->idUsuario){
            throw new CustomException('No tienes los permisos necesarios.', 403);
        }
    }
}