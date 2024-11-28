<?php
namespace App\Validators\Titulo\Registrar;

use App\Exceptions\CustomException;
use App\Validators\Validator;

class ValidarTituloSinRepetir implements Validator{

    public $nombre;
    public $institucion;

    public function __construct($nombre, $institucion){
        $this->nombre=$nombre;
        $this->institucion=$institucion;
    }

    public function validate(): void{
        $usuario = request()->attributes->get('usuarioValidado');
        $tituloDetalles = $usuario->tituloDetalles;
        if($tituloDetalles){
            foreach($tituloDetalles as $tituloDetalle){
                $titulo = $tituloDetalle->titulo;
                if($titulo->nombre==$this->nombre
                && $titulo->institucion == $this->institucion){
                    throw new CustomException('No puedes tener titulos replicados', 400);
                }
            }
        }
    }
}