<?php

namespace App\DTO\Empresa;

use App\DTO\ExperienciaLaboral\ExperienciaLaboralRespuestaDTO;
use App\DTO\Horario\HorarioRespuestaDTO;
use App\DTO\Pasantia\PasantiaRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Models\Empresa;

class EmpresaDetalleDTO{
    public  $id;
    public $nombre;
    public $referente;
    public $cuil;
    public $usuario;

    public $ubicacion;
    public $horarios;

    public $pasantias;
    public $experienciasLaborales;

    public function __construct(Empresa $empresa){
        $direccion = $empresa->direccion;
        $horarios = $empresa->horarios;
        $pasantias = $empresa->getPasantiasPublicas();
        $usuario = $empresa->usuario;

        $this->id = $empresa->id;
        $this->nombre = $empresa->nombre;
        $this->referente = $empresa->referente;
        $this->cuil = $empresa->cuil_cuit;
        $this->ubicacion = $direccion ? new UbicacionRespuestaDTO($direccion) : null;
        $this->horarios = $horarios 
            ? $horarios->map(function ($horario){
                return new HorarioRespuestaDTO($horario);
            })
            : [];
        $this->pasantias = $pasantias
            ? $pasantias->map(function ($pasantia){
                return new PasantiaRespuestaDTO($pasantia, withEmpresa:false);
            })
            :[];
        $this->experienciasLaborales = $empresa->experienciasLaborales->map(function($exp){
            return new ExperienciaLaboralRespuestaDTO($exp);
        });
        $this->usuario = $usuario ? new UsuarioRespuestaDTO($usuario) : null;
    }
}