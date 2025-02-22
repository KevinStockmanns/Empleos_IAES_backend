<?php
namespace App\DTO\Usuario;

use App\DTO\Contacto\ContactoRespuestaDTO;
use App\DTO\ExperienciaLaboral\ExperienciaLaboralRespuestaDTO;
use App\DTO\Habilidad\HabilidadRespuestaDTO;
use App\DTO\LicenciaConducir\LicenciaConducirRespuestaDTO;
use App\DTO\PerfilProfesional\PerfilProfesionalRespuestaDTO;
use App\DTO\Titulo\TituloRespuestaDTO;
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
    public $fotoPerfil;

    public $perfilProfesional;
    public $contacto;
    public $ubicacion;
    public $habilidades;
    public $educacion;
    public $experienciaLaboral;
    public $licenciaConducir;
    

    public function __construct(Usuario $usuario){
        // $usuario->load('experienciasLaborales');
        $contacto = $usuario->contacto;
        $perfilP = $usuario->perfilProfesional;
        $direccion = $usuario->direccion;
        $habilidades = $usuario->habilidades;
        $expL = $usuario->experienciasLaborales;
        $lic = $usuario->licenciaConducir;

        $this->id = $usuario->id ?? null;
        $this->correo = $usuario->correo ?? null;
        $this->nombre = $usuario->nombre ?? null;
        $this->apellido = $usuario->apellido ?? null;
        $this->dni = $usuario->dni ?? null;
        $this->fechaNacimiento = $usuario->fecha_nacimiento ?? null;
        $this->estado = $usuario->estado ?? null;
        $this->rol = $usuario->rol->nombre ?? null;
        $this->fotoPerfil = $usuario->foto_perfil;
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
            $this->educacion = $usuario->tituloDetalles
            ->map(fn($titulo) => new TituloRespuestaDTO($titulo))
            ->toArray();
        $this->experienciaLaboral = $expL 
            ? $expL->map(function($exp){
                return new ExperienciaLaboralRespuestaDTO($exp);
            })->toArray()
            : [];
        $this->licenciaConducir = $lic ? new LicenciaConducirRespuestaDTO($lic) : null;

    }
}