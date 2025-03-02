<?php

namespace App\DTO\Pasantia;

use App\Models\Empresa;

class PasantiaEmpresaDTO{
    public $id;
    public $nombre;

    public function __construct(Empresa $empresa){
        $this->id = $empresa->id;
        $this->nombre = $empresa->nombre;
        
    }
}