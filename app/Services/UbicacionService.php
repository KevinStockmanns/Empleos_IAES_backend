<?php
namespace App\Services;

use App\Models\Direccion;
use App\Models\Localidad;
use App\Models\Pais;
use App\Models\Provincia;

class UbicacionService{
    public function registrarOrBuscar($data){
        $pais = Pais::createOrFirst([
            'nombre'=>$data['pais'],
        ]);
        $provincia = Provincia::createOrFirst([
            'nombre'=> $data['provincia'],
            "pais_id"=> $pais->id,
        ]);
        $localidad = Localidad::createOrFirst([
            "nombre"=> $data['localidad'],
            'codigo_postal' => $data['codigo_postal'] ?? null, 
            "provincia_id"=>$provincia->id
            
        ]);
        $direccion = Direccion::createOrFirst([
            'barrio'=> $data['barrio'],
            'calle'=>$data['direccion'],
            'numero'=> isset($data['numero']) ? $data['numero'] : null,
            'piso'=> isset($data['piso']) ? $data['piso'] : null,
            'localidad_id'=> $localidad->id,
        ]);

        return $direccion;
    }
}