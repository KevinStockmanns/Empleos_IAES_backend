<?php

namespace App\DTO\Titulo;

use App\Models\Titulo;

class TituloListadoDTO{
    public $id;
    public $nombre;
    public $alias;
    public $institucion;



    public function __construct(Titulo $titulo){
        $this->id = $titulo->id;
        $this->nombre = $titulo->nombre;
        $this->alias = $titulo->alias;
        $this->institucion = $titulo->institucion;
    }
}