<?php
namespace App\DTO\Titulo;

use App\Models\TituloDetalle;

class TituloRespuestaDTO{
    public $nombre;
    public $institucion;
    public $alias;
    public $fechaInicio;
    public $fechaFin;
    public $promedio;
    public $descripcion;
    public $tipo;

    public function __construct(TituloDetalle $tituloDetalle){
        $titulo = $tituloDetalle->titulo;

        $this->nombre=$titulo->nombre;
        $this->institucion=$titulo->institucion;
        $this->alias=$titulo->alias;

        $this->fechaInicio = $tituloDetalle->fecha_inicio;
        $this->fechaFin = $tituloDetalle->fecha_fin;
        $this->promedio = $tituloDetalle->promedio;
        $this->descripcion = $tituloDetalle->descripcion;
        $this->tipo = $tituloDetalle->tipo;
    }
}