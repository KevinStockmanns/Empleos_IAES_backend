<?php
namespace App\DTO\PerfilProfesional;

use App\Models\PerfilProfesional;

class PerfilProfesionalRespuestaDTO{
    public $cargo;
    public $cartaPresentacion;
    public $disponibilidad;
    public $disponibilidadMudanza;

    public function __construct(PerfilProfesional $perfil) {
        $this->cargo = $perfil->cargo;
        $this->cartaPresentacion = $perfil->carta_presentacion;
        $this->disponibilidad = $perfil->disponibilidad;
        $this->disponibilidadMudanza = $perfil->disponibilidad_mudanza;
    }
}