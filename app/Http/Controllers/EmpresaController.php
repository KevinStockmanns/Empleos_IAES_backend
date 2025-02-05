<?php

namespace App\Http\Controllers;

use App\DTO\Empresa\EmpresaDetalleDTO;
use App\DTO\Empresa\EmpresaListadoDTO;
use App\DTO\Empresa\EmpresaRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\Empresa\EmpresaRegistrarRequest;
use App\Http\Requests\UbicacionRequest;
use App\Models\Empresa;
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

    public function listarEmpresas(Request $req){
        $page = $req->input('page', 0);
        $size = $req->input('size', 15);

        $query = Empresa::query();

        $empresas = $query->paginate($size);


        return response()->json([
            'empresas'=> $empresas->map(function($empresa){
                return new EmpresaListadoDTO($empresa);
            })
        ]);
    }


    public function getEmpresa($id){
        $empresa = Empresa::find($id);
        if(!$empresa){
            throw new CustomException('La empresa no se encuentra en la base de datos.', 404);
        }

        return response()->json(new EmpresaDetalleDTO($empresa));
    }


}
