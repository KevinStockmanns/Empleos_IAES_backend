<?php
namespace App\DTO\ExperienciaLaboral;

use App\Models\ExperienciaLaboral;

class ExperienciaLaboralRespuestaDTO{
    public $puesto;
    public $empresa;
    public $fechaInicio;
    public $fechaTerminacion;
    public $descripcion;
    public $idEmpresa;

    public function __construct(ExperienciaLaboral $exp){
        $empresa = $exp->empresa;
        $this->puesto = $exp->puesto;
        $this->empresa = $exp->empresa ?? $empresa->nombre;
        $this->fechaInicio = $exp->fecha_inicio;
        $this->fechaTerminacion = $exp->fecha_terminacion;
        $this->descripcion = $exp->descripcion;
        $this->idEmpresa = $empresa->id ?? null;
    }
}