<?php
namespace App\DTO\Usuario;
class UsuarioRespuestaDTO
{
    public $id;
    public $correo;
    public $nombre;
    public $apellido;
    public $dni;
    public $fechaNacimiento;
    public $estado;

    public function __construct($usuario)
    {
        $this->id = $usuario->id;
        $this->correo = $usuario->correo;
        $this->nombre = $usuario->nombre;
        $this->apellido = $usuario->apellido;
        $this->dni = $usuario->dni;
        $this->fechaNacimiento = $usuario->fecha_nacimiento;
        $this->estado = $usuario->estado;
    }
}