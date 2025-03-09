<?php
namespace App\DTO\Pasantia;

use App\DTO\Empresa\EmpresaRespuestaDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Models\Pasantia;

class PasantiaRespuestaDTO{
    public $id;
    public $fechaInicio;
    public $fechaFinal;
    public $titulo;


    public function __construct(Pasantia $pasantia, $withUsuario = true, $withEmpresa = true){
        $this->id = $pasantia->id;
        $this->titulo = $pasantia->titulo;
        $this->fechaInicio = $pasantia->fecha_inicio ? $pasantia->fecha_inicio->format('d/m/Y') : null;
        $this->fechaFinal = $pasantia->fecha_final ? $pasantia->fecha_final->format('d/m/Y') : null;


        if( $withEmpresa ){
            $empresa = $pasantia->empresa;
            $this->empresa = $empresa ? new EmpresaRespuestaDTO($empresa) : null;
        }
        if( $withUsuario ){
            $usuarios = $pasantia->usuarios;
            $this->usuario = $usuarios ? 
            $usuarios->map(function($usuario){
                return [
                    'id'=>$usuario->id,
                    'nombre'=>$usuario->nombre,
                    'apellido'=>$usuario->apellido,
                    'nota'=>$usuario->pivot->nota,
                ];
            })  : [];
        }
    }
}