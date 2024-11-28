<?php
namespace App\Services;

use App\Models\Titulo;
use App\Models\TituloDetalle;

class TituloService{


    public function registrarTitulo($data, $idUsuario): TituloDetalle{
        $titulo = $this->findOrRegistrarTitulo($data['nombre'], $data['institucion'], $data['alias'] ?? null);

        return TituloDetalle::create([
            'fecha_inicio'=> $data['fechaInicio'],
            'fecha_fin'=> $data['fechaFin'] ?? null,
            'promedio'=> $data['promedio'] ?? null,
            'tipo'=> $data['tipo'],
            'descripcion'=> $data['descripcion'] ?? null,
            'titulo_id'=> $titulo->id,
            'usuario_id'=> $idUsuario
        ]);
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