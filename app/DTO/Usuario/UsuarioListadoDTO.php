<?php
namespace App\DTO\Usuario;

use App\Models\Usuario;

class UsuarioListadoDTO{
    public $id;
    public $correo;
    public $nombre;
    public $apellido;
    public $dni;
    public $fechaNacimiento;
    public $estado;
    public $rol;
    public $estadoPerfil;
    public $disponibilidad;

    public function __construct(Usuario $usuario, $perfilCompletoDto = null) {
        // Cargamos la relación perfilProfesional si no está cargada
        $usuario->loadMissing('perfilProfesional');

        $this->id = $usuario->id;
        $this->correo = $usuario->correo;
        $this->nombre = $usuario->nombre;
        $this->apellido = $usuario->apellido;
        $this->dni = $usuario->dni;
        $this->fechaNacimiento = $usuario->fecha_nacimiento;
        $this->estado = $usuario->estado;
        $this->rol = $usuario->rol->nombre;
        $this->estadoPerfil = $perfilCompletoDto->completo ?? 0;

        $perfilProf = $usuario->perfilProfesional;
        $this->disponibilidad = $perfilProf ? $perfilProf->disponibilidad : null;
    }
}