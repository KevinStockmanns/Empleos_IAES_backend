<?php

namespace App\Http\Controllers;

use App\DTO\PaginacionDTO;
use App\DTO\Titulo\TituloListadoDTO;
use App\Models\Habilidad;
use App\Models\Titulo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;




    public function listarTitulos(){
        $titulos = Titulo::where('visible', true)
            ->where('alias', 'IAES')->get();

        
        return response()->json(new PaginacionDTO(
            $titulos->map(function($tit){
                return new TituloListadoDTO($tit);
            }),
            100,
            1,
            1,
            $titulos->count()
        ));
    }



    public function listarHabilidades(){
        $habilidades = Habilidad::where('visible', true)
            ->withCount('usuarios')
            ->orderBy('usuarios_count', 'desc')
            ->get();

        return response()->json($habilidades->map(function($hab){
            return [
                'nombre'=>$hab->nombre,
                'tipo'=>$hab->tipo,
            ];
        }));
    }
}
