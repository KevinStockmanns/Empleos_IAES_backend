<?php
namespace App\DTO\Pasantia;

use App\DTO\Empresa\EmpresaRespuestaDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Models\Pasantia;

class PasantiaRespuestaDTO{
    public $id;
    public $fechaInicio;
    public $fechaFinal;
    public $nota;


    public function __construct(Pasantia $pasantia, $withUsuario = true, $withEmpresa = true){
        $this->id = $pasantia->id;
        $this->fechaInicio = $pasantia->fecha_inicio->format('d/m/Y');
        $this->fechaFinal = $pasantia->fecha_final->format('d/m/Y');
        $this->nota = $pasantia->nota ? number_format($pasantia->nota, 2) : null;


        if( $withEmpresa ){
            $empresa = $pasantia->empresa;
            $this->empresa = $empresa ? new EmpresaRespuestaDTO($empresa) : null;
        }
        if( $withUsuario ){
            $usuario = $pasantia->usuario;
            $this->usuario = $usuario ? new UsuarioRespuestaDTO($usuario) : null;
        }
    }
}