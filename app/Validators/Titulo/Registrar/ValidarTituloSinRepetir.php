<?php
namespace App\Validators\Titulo\Registrar;

use App\Exceptions\CustomException;
use App\Validators\Validator;

class ValidarTituloSinRepetir implements Validator{

    public $data;

    public function __construct($data){
        $this->data=$data;
    }

    public function validate(): void{
        $usuario = request()->attributes->get('usuarioValidado');
        if(count($usuario->tituloDetalles)>0){
            throw new CustomException('El usuario ya tiene titulos cargados', 400);
        }

        $titulosUnicos = []; // Arreglo para detectar duplicados
        foreach($this->data['titulos'] as $tituloDto){
            $key = $tituloDto['nombre'] . '|' . $tituloDto['institucion']; // Combinar nombre + institución como clave única

            if (isset($titulosUnicos[$key])) {
                // Si ya existe, lanzar excepción
                throw new CustomException(
                    "El título '{$tituloDto['nombre']}' de la institución '{$tituloDto['institucion']}' está duplicado en la solicitud.",
                    400
                );
            }

            $titulosUnicos[$key] = true; // Marcar este título como registrado
        }
    }
}