<?php
namespace App\Services;

use App\Models\Direccion;
use App\Models\Localidad;
use App\Models\Pais;
use App\Models\Provincia;

class UbicacionService{
    public function registrarOrBuscar($data) {
        $pais = Pais::updateOrCreate(
            ['nombre' => $data['pais']]
        );

        $provincia = Provincia::updateOrCreate(
            [
                'pais_id' => $pais->id,
                'nombre' => $data['provincia']
            ]
        );

        $localidad = Localidad::updateOrCreate(
            [
                'provincia_id' => $provincia->id,
                'nombre' => $data['localidad']
            ],
            [
                'codigo_postal' => $data['codigo_postal'] ?? null
            ]
        );

        $direccion = Direccion::updateOrCreate(
            [
                'localidad_id' => $localidad->id,
                'calle' => $data['direccion']
            ],
            [
                'barrio' => $data['barrio'],
                'numero' => $data['numero'] ?? null,
                'piso' => $data['piso'] ?? null
            ]
        );

        return $direccion;
    }
}