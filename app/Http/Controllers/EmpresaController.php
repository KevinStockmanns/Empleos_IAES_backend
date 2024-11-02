<?php

namespace App\Http\Controllers;

use App\DTO\Empresa\EmpresaRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\Http\Requests\Empresa\EmpresaRegistrarRequest;
use App\Http\Requests\UbicacionRequest;
use App\Services\EmpresaService;
use App\Services\UbicacionService;
use Illuminate\Http\Request;

class EmpresaController extends Controller{
    private EmpresaService $empresaService;
    private UbicacionService $ubicacionService;
    public function __construct(EmpresaService $empresaService, UbicacionService $ubicacionService){
        $this->empresaService=$empresaService;
        $this->ubicacionService= $ubicacionService;
    }
    public function postEmpresa(EmpresaRegistrarRequest $req){
        $data = $req->validated();
        $empresa = $this->empresaService->registrarEmpresa($data);

        return response()->json(new EmpresaRespuestaDTO($empresa));
    }

    public function cambiarUbicacion(UbicacionRequest $req){
        $data = $req->validated();
        $idEmpresa = $req->route('id');
        $direccion = $this->empresaService->cambiarUbicacion($idEmpresa, $data);
        return response()->json(new UbicacionRespuestaDTO($direccion));
    }
}
