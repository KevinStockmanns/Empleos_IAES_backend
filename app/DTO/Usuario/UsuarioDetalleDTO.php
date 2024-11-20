<?php
namespace App\DTO\Usuario;

use App\DTO\Contacto\ContactoRespuestaDTO;
use App\DTO\Habilidad\HabilidadRespuestaDTO;
use App\DTO\PerfilProfesional\PerfilProfesionalRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\Models\Usuario;

class UsuarioDetalleDTO{
    public $id;
    public $nombre;
    public $apellido;
    public $correo;
    public $dni;
    public $fechaNacimiento;
    public $estado;
    public $rol;

    public $perfilProfesional;
    public $contacto;
    public $ubicacion;
    public $habilidades;
    

    public function __construct(Usuario $usuario){
        $contacto = $usuario->contacto;
        $perfilP = $usuario->perfilProfesional;
        $direccion = $usuario->direccion;
        $habilidades = $usuario->habilidades;

        $this->id = $usuario->id ?? null;
        $this->correo = $usuario->correo ?? null;
        $this->nombre = $usuario->nombre ?? null;
        $this->apellido = $usuario->apellido ?? null;
        $this->dni = $usuario->dni ?? null;
        $this->fechaNacimiento = $usuario->fecha_nacimiento ?? null;
        $this->estado = $usuario->estado ?? null;
        $this->rol = $usuario->rol->nombre ?? null;
        $this->contacto = $contacto 
            ? new ContactoRespuestaDTO($contacto)
            : null;
        $this->perfilProfesional = $perfilP ? new PerfilProfesionalRespuestaDTO($perfilP):null;
        $this->ubicacion = $direccion ? new UbicacionRespuestaDTO($direccion) :null;
        $this->habilidades = $habilidades
            ? array_map(function($hab){
                return new HabilidadRespuestaDTO($hab);
            }, $habilidades->all())
            : [];
    }
}