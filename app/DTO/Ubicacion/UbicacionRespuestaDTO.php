<?php

namespace App\DTO\Ubicacion;

use App\Models\Direccion;

class UbicacionRespuestaDTO{
    public $idDireccion;

    public $pais;
    public $provincia;
    public $localidad;
    public $calle;
    public $numero;
    public $piso;

    public function __construct(Direccion $direccion){
        $localidad = $direccion->localidad;
        $provincia = $localidad->provincia;
        $this->piso = $direccion->piso;
        $this->numero = $direccion->numero;
        $this->calle = $direccion->calle;
        $this->localidad = $localidad->nombre;
        $this->provincia = $provincia->nombre;
        $this->pais = $provincia->pais->nombre;
        $this->idDireccion = $direccion->id;
    }
}