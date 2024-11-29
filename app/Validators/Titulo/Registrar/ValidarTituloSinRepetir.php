<?php
namespace App\Validators\Titulo\Registrar;

use App\Enums\AccionCrudEnum;
use App\Exceptions\CustomException;
use App\Models\TituloDetalle;
use App\Validators\Validator;

class ValidarTituloSinRepetir implements Validator
{

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate(): void
    {
        $usuario = request()->attributes->get('usuarioValidado');

        $titulosUnicos = [];
        $titulo = null;
        $tituloDetalles = request()->attributes->get('usuarioValidado')->tituloDetalles;
        foreach ($this->data['titulos'] as $key => $tituloDto) {
                if ($tituloDto['accion'] == AccionCrudEnum::AGREGAR->value) {
                    $nombreNormalizado = mb_strtolower($tituloDto['nombre']);
                $institucionNormalizada = mb_strtolower($tituloDto['institucion']);
                
                $claveUnica = $nombreNormalizado . '|' . $institucionNormalizada;

                if (isset($titulosEnPeticion[$claveUnica])) {
                    throw new CustomException(
                        'No pueden existir títulos duplicados en la misma petición', 
                        400, 
                        'titulos.' . $key
                    );
                }
                $titulosEnPeticion[$claveUnica] = true;
                $existe = $tituloDetalles->first(function($detalle) use ($tituloDto) {
                    $titulo = $detalle->titulo;
                    return mb_strtolower($titulo->nombre) == mb_strtolower($tituloDto['nombre'])
                        && mb_strtolower($titulo->institucion) == mb_strtolower($tituloDto['institucion']);
                });
                if($existe){
                    throw new CustomException('El titulo ya se encuentra en la base de datos',400, 'titulos.'.$key);
                }
            }
            if ($tituloDto['accion'] == AccionCrudEnum::ACTUALIZAR->value) {
                if(isset($tituloDto['nombre']) || isset($tituloDto['institucion'])){
                    $detalleActual = $tituloDetalles->find($tituloDto['id']);
                    $nombre = mb_strtolower($tituloDto['nombre'] ?? $detalleActual->nombre);
                    $institucion = mb_strtolower($tituloDto['institucion'] ?? $detalleActual->institucion);
                    $data = $this->data;
                    $existe = $tituloDetalles->first(function($detalle) use ($tituloDto,$nombre,$institucion,$data){
                        $existeEnDB = mb_strtolower($detalle->nombre)==$nombre && mb_strtolower($detalle->institucion)==$institucion && $tituloDto['id']!=$detalle->id;
                        $seraModificado =false;
                        foreach($data['titulos'] as $tituloDto2){
                            if($tituloDto['id'] == $tituloDto2['id']){
                                if($tituloDto['accion'] == AccionCrudEnum::ELIMINAR->value){
                                    $seraModificado =true;
                                    break;
                                }
                                if($tituloDto['accion'] == AccionCrudEnum::ACTUALIZAR->value){
                                    if(isset($tituloDto2['nombre'])){
                                        $seraModificado = mb_strtolower($tituloDto2['nombre'])!= mb_strtolower($tituloDto['nombre']);
                                        break;
                                    }
                                    if(isset($tituloDto2['institucion'])){
                                        $seraModificado = mb_strtolower($tituloDto2['institucion'])!= mb_strtolower($tituloDto['institucion']);
                                        break;
                                    }
                                }
                            }
                        }
                        return $existeEnDB && !$seraModificado;
                    });
                    if($existe){
                        throw new CustomException('No puede haber dos titulos con el mismo nombre e institución',400, 'titulos.'.$key);
                    }
                }
            }
        }
    }
}