<?php

namespace App\DTO\Pasantia;

use App\Models\Pasantia;

class PasantiaListadoDTO{
    public $id;
    public $titulo;
    public $estado;
    public $empresa;
    public $usuarios;

    public function __construct(Pasantia $pasantia, $idUser = null){
        $usuarios = $idUser
            ? $pasantia->usuarios()->where('usuarios.id', $idUser)->get()
            : $pasantia->usuarios;
        $empresa = $pasantia->empresa;
        $this->id = $pasantia->id;
        $this->titulo = $pasantia->titulo;
        $this->estado = $pasantia->estado;
        $this->empresa = $empresa ? new PasantiaEmpresaDTO($empresa) : null;
        $this->usuarios = $usuarios 
            ? $usuarios->map(function($usuario){
                return new PasantiaUsuarioDTO($usuario);
            })
            : [];
    }
}