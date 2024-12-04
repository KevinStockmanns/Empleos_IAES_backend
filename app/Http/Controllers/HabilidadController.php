<?php

namespace App\Http\Controllers;

use App\DTO\Habilidad\HabilidadRespuestaDTO;
use App\Models\Habilidad;
use Illuminate\Http\Request;

class HabilidadController extends Controller
{
    public function getHabilidades(){
        $habilidades = Habilidad::where('visible', true)
            ->orderBy('tipo', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->get();
        return response()->json(
            ['habilidades'=> $habilidades->map(function($hab){
                return new HabilidadRespuestaDTO($hab);
            })]
        );
    }
}
