<?php

namespace App\DTO\Pasantia;

use App\Models\Pasantia;

class PasantiaDetalleDTO{
    public $id;
    public $titulo;
    public $estado;
    public $fechaInicio;
    public $fechaFinal;
    public $descripcion;
    public $empresa;
    public $usuarios;


    public function __construct(Pasantia $pasantia){
        $usuarios = $pasantia->usuarios;
        $empresa = $pasantia->empresa;
        $this->id = $pasantia->id;
        $this->titulo = $pasantia->titulo;
        $this->fechaInicio = $pasantia->fecha_inicio;
        $this->fechaFinal = $pasantia->fecha_final;
        $this->descripcion = $pasantia->desc;
        $this->estado = $pasantia->estado;
        $this->empresa = $empresa ? new PasantiaEmpresaDTO($empresa) : null;
        $this->usuarios = $usuarios 
            ? $usuarios->map(function($usuario){
                return new PasantiaUsuarioDTO($usuario);
            })
            : [];
    }
}