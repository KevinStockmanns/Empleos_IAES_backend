<?php
namespace App\Validators\Titulo\Registrar;

use App\Enums\AccionCrudEnum;
use App\Exceptions\CustomException;
use App\Validators\Validator;

class ValidarIDAccion implements Validator{
    private $data;

    public function __construct($data){
        $this->data=$data;
    }

    public function validate(): void{
        foreach($this->data['titulos'] as $key => $tituloDto){
            $field = 'titulos.'.$key.'.';
            if(
                ($tituloDto['accion'] == AccionCrudEnum::ACTUALIZAR->value 
                || $tituloDto['accion'] == AccionCrudEnum::ELIMINAR->value)
                && !isset($tituloDto['id'])
                ){
                    throw new CustomException('Para actualizar o eliminar el titulo es requerido el id.', 400, $field.'id');
            }
            if($tituloDto['accion'] == AccionCrudEnum::AGREGAR->value ){
                if(!isset($tituloDto['nombre'])){
                    throw new CustomException('El nombre del titulo es requerido', 400, $field.'nombre');
                }
                if(!isset($tituloDto['institucion'])){
                    throw new CustomException('La institución es requerido', 400, $field.'institucion');
                }
                if(!isset($tituloDto['fechaInicio'])){
                    throw new CustomException('La fecha de inicio es requerida', 400, $field.'fechaInicio');
                }
                if(!isset($tituloDto['tipo'])){
                    throw new CustomException('El tipo de titulo es requerido', 400, $field.'tipo');
                }
            }
        }
    }
}