<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Titulo;
use App\Models\TituloDetalle;
use App\Models\Usuario;
use App\Validators\Titulo\Registrar\ValidarIDAccion;
use App\Validators\Titulo\Registrar\ValidarTituloSinRepetir;
use App\Validators\ValidatorHandler;

class TituloService{


    public function registrarTitulo($data, Usuario $usuario){
        $handler = new ValidatorHandler();
        $handler->addValidator(new ValidarIDAccion($data));
        $handler->addValidator(new ValidarTituloSinRepetir($data));
        $handler->validate();

            
        $tituloDetalles = $usuario->tituloDetalles;
        foreach($data['titulos'] as $tituloDto){
            if($tituloDto['accion'] == 'AGREGAR'){
                $titulo = $this->findOrRegistrarTitulo($tituloDto['nombre'], $tituloDto['institucion'], $tituloDto['alias'] ?? null);

                $tituloDetalles[]= TituloDetalle::create([
                    'fecha_inicio'=> $tituloDto['fechaInicio'],
                    'fecha_final'=> $tituloDto['fechaFin'] ?? null,
                    'promedio'=> $tituloDto['promedio'] ?? null,
                    'tipo'=> $tituloDto['tipo'],
                    'descripcion'=> $tituloDto['descripcion'] ?? null,
                    'titulo_id'=> $titulo->id,
                    'usuario_id'=> $usuario->id
                ]);
            }else if($tituloDto['accion'] == 'ELIMINAR'){
                $eliminado = false;
                foreach ($tituloDetalles as $key => $detalle) {
                    if ($detalle->id === $tituloDto['id']) {
                        $detalle->delete(); 
                        unset($tituloDetalles[$key]); 
                        $eliminado = true;
                        break; 
                    }
                }
                if(!$eliminado){
                    throw new CustomException('No se econtro el titulo para eliminar',404);
                }
            }else if($tituloDto['accion'] == 'ACTUALIZAR'){
                $actualizado = false;
                foreach($tituloDetalles as $detalle){
                    if($detalle->id == $tituloDto['id']){
                        if(isset($tituloDto['fechaInicio'])){
                            $detalle->fecha_inicio = $tituloDto['fechaInicio'];
                        }
                        if(isset($tituloDto['fechaFin'])){
                            $detalle->fecha_final = $tituloDto['fechaFin'];
                        }
                        if(isset($tituloDto['promedio'])){
                            $detalle->promedio = $tituloDto['promedio'];
                        }
                        if(isset($tituloDto['descripcion'])){
                            $detalle->descripcion = $tituloDto['descripcion'];
                        }
                        if(isset($tituloDto['tipo'])){
                            $detalle->tipo = $tituloDto['tipo'];
                        }
                        if(
                            isset($tituloDto['nombre'])
                            || isset($tituloDto['institucion'])
                            || isset($tituloDto['alias'])
                        ){
                            $titulo = $detalle->titulo;
                            $newTitulo = $this->findOrRegistrarTitulo(
                                $tituloDto['nombre'] ?? $titulo->nombre,
                                $tituloDto['institucion'] ?? $titulo->institucion,
                                $tituloDto['alias'] ?? $titulo->alias
                            );
                            $detalle->titulo_id = $newTitulo->id;
                            // dd($titulo, $newTitulo);
                        }
                        $detalle->save();
                        $actualizado=true;
                        break;
                    }
                }
                if(!$actualizado){
                    throw new CustomException('No se econtro el titulo para actualizar',404);
                }
            }
        }

        $usuario->load('tituloDetalles');
        return $tituloDetalles;
    }

    private function findOrRegistrarTitulo(string $nombre, string $institucion, string|null $alias=null): Titulo{
        return Titulo::firstOrCreate([
            'nombre'=>$nombre,
            'institucion'=>$institucion,
            'alias'=>$alias
        ]);
    }

    private function crearTituloDetalle($data, $idTitulo, $idUsuario): TituloDetalle{
        return TituloDetalle::create([
            'fecha_inicio'=> $data['fechaInicio'],
            'fecha_final'=> $data['fechaFin'] ?? null,
            'promedio'=> $data['promedio'] ?? null,
            'tipo'=> $data['tipo'],
            'descripcion'=> $data['descripcion'] ?? null,
            'titulo_id'=> $idTitulo,
            'usuario_id'=> $idUsuario
        ]);
    }
}