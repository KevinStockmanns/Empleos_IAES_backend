<?php
namespace App\DTO\Usuario;

use App\Enums\EstadoUsuarioEnum;
use App\Models\Usuario;
class UsuarioRespuestaDTO
{
    public $id;
    public $correo;
    public $nombre;
    public $apellido;
    public $dni;
    public $fechaNacimiento;
    public $estado;
    public $rol;
    public $token;
    public $adminInfo = [];

    public function __construct(Usuario $usuario, $token = null)
    {
        $this->id = $usuario->id;
        $this->correo = $usuario->correo;
        $this->nombre = $usuario->nombre;
        $this->apellido = $usuario->apellido;
        $this->dni = $usuario->dni;
        $this->fechaNacimiento = $usuario->fecha_nacimiento;
        $this->estado = $usuario->estado;
        $this->rol = $usuario->rol->nombre;
        $this->token = $token;
        

        if($usuario->isAdmin()){
            logger("deberia cargar admin");
            $usuariosSolicitados = Usuario::where('estado', EstadoUsuarioEnum::SOLICITADO->value)->count();
            logger($usuariosSolicitados);
            if ($usuariosSolicitados > 0) {
                $this->adminInfo['solicitudUsuarios'] = $usuariosSolicitados;
            }
        }
    }
}