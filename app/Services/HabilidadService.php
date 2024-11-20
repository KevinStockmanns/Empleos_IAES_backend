<?php
namespace App\Services;

use App\Models\Habilidad;

class HabilidadService{


    public function buscarOCrear($data): array{
        $habilidadesReq = $data['habilidades'];
        $habilidades = [];

        foreach ($habilidadesReq as $habilidadReq) {
            $habilidad = Habilidad::where('nombre', $habilidadReq['nombre'])
                ->where('tipo', $habilidadReq['tipo'])->first();
            if (!$habilidad){
                $habilidad = Habilidad::create([
                    'nombre'=>$habilidadReq['nombre'],
                    'tipo'=>$habilidadReq['tipo'],
                    'visible'=> false,
                ]);
            }
            $habilidades[] = $habilidad;
        }
        return $habilidades;
    }
}