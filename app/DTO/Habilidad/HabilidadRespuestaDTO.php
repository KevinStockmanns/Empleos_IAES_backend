<?php
namespace App\DTO\Habilidad;

use App\Models\Habilidad;

class HabilidadRespuestaDTO{
    public $nombre;
    public $tipo;

    public function __construct(Habilidad $habilidad){
        $this->nombre = $habilidad->nombre;
        $this->tipo = $habilidad->tipo;
    }
}