<?php

namespace App\DTO\LicenciaConducir;

use App\Models\LicenciaConducir;

class LicenciaConducirRespuestaDTO{
    public $id;
    public $categoria;
    public $vehiculoPropio;

    public function __construct(LicenciaConducir $lic){
        $this->id = $lic->id;
        $this->categoria = $lic->categoria;
        $this->vehiculoPropio = $lic->vehiculo_propio;
    }
}