<?php
namespace App\Services;

use App\Models\Titulo;
use App\Models\TituloDetalle;

class TituloService{


    public function registrarTitulo($data, $idUsuario): array{
        $titulos = [];
        foreach($data['titulos'] as $tituloDto){
            $titulo = $this->findOrRegistrarTitulo($tituloDto['nombre'], $tituloDto['institucion'], $tituloDto['alias'] ?? null);

            $titulos[]= TituloDetalle::create([
                'fecha_inicio'=> $tituloDto['fechaInicio'],
                'fecha_fin'=> $tituloDto['fechaFin'] ?? null,
                'promedio'=> $tituloDto['promedio'] ?? null,
                'tipo'=> $tituloDto['tipo'],
                'descripcion'=> $tituloDto['descripcion'] ?? null,
                'titulo_id'=> $titulo->id,
                'usuario_id'=> $idUsuario
            ]);
        }
        return $titulos;
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
            'fecha_fin'=> $data['fechaFin'] ?? null,
            'promedio'=> $data['promedio'] ?? null,
            'tipo'=> $data['tipo'],
            'descripcion'=> $data['descripcion'] ?? null,
            'titulo_id'=> $idTitulo,
            'usuario_id'=> $idUsuario
        ]);
    }
}