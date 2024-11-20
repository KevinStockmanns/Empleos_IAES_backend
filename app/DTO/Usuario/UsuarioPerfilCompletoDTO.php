<?php
namespace App\DTO\Usuario;

use App\Models\Usuario;

class UsuarioPerfilCompletoDTO{
    public $completo=0;
    public $datos;


    public function __construct($porcentaje, $datos){
        $this->completo = $porcentaje;
        $this->datos = $datos;
    }
}