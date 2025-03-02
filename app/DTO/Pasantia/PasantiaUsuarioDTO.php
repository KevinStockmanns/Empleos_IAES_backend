<?php

namespace App\DTO\Pasantia;

use App\Models\Usuario;

class PasantiaUsuarioDTO{
    public $id;
    public $apellido;
    public $nombre;
    public $nota;

    public function __construct(Usuario $usuario){
        $this->id = $usuario->id;
        $this->nombre = $usuario->nombre;
        $this->apellido = $usuario->apellido;
        $this->nota = $usuario->pivot->nota ? number_format($usuario->pivot->nota, 2) : null;
    }
}