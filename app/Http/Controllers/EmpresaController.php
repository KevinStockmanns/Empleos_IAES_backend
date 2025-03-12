<?php

namespace App\Http\Controllers;

use App\DTO\Empresa\EmpresaDetalleDTO;
use App\DTO\Empresa\EmpresaListadoDTO;
use App\DTO\Empresa\EmpresaRespuestaDTO;
use App\DTO\PaginacionDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\Empresa\EmpresaCRUDRequest;
use App\Http\Requests\UbicacionRequest;
use App\Models\Empresa;
use App\Rules\OwnerOrAdmin;
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
    public function postEmpresa(EmpresaCRUDRequest $req){
        $data = $req->validated();
        if(isset($data['id'])){
            $empresa = $this->empresaService->actualizarEmpresa($data);
        }else{
            $empresa = $this->empresaService->registrarEmpresa($data);
        }

        return response()->json(new EmpresaRespuestaDTO($empresa));
    }

    public function cambiarUbicacion(UbicacionRequest $req){
        $data = $req->validated();
        $idEmpresa = $req->route('id');
        $direccion = $this->empresaService->cambiarUbicacion($idEmpresa, $data);
        return response()->json(new UbicacionRespuestaDTO($direccion));
    }

    public function listarEmpresas(Request $req){
        $page = $req->input('page', 1);
        $size = $req->input('size', 20);

        $query = Empresa::query();

        if($req->has('nombre')){
            $nombre = $req->get('nombre');
            $query->where('nombre', 'like', "%$nombre%")
                ->orWhere('cuil_cuit', 'like', "%$nombre%");
        }
        if($req->has('referente')){
            $ref = $req->get('referente');
            $query->where('referente', 'like', "%$ref%")
                ->orWhereHas('usuario',function($user) use($ref){
                    $user->where('nombre', 'like', "$ref")
                        ->orWhere('apellido', 'like', "$ref");
                });
        }

        $empresas = $query->paginate($size);


        return response()->json(new PaginacionDTO(
            $empresas->map(function($empresa){
                return new EmpresaListadoDTO($empresa);
            }),
            $size,
            $page,
            $empresas->lastPage(),
            $empresas->total()
        ));
    }


    public function getEmpresa($id){
        $empresa = Empresa::find($id);
        if(!$empresa){
            throw new CustomException('La empresa no se encuentra en la base de datos.', 404);
        }

        return response()->json(new EmpresaDetalleDTO($empresa));
    }




    public function deleteEmpresa(Request $req, $idEmpresa){
        $validated = $req->validate([
            'idUsuario' => ['required', new OwnerOrAdmin()],
        ],[
            'idUsuario.required'=>'El id del usuario es requerido.',
        ]);

        $empresa = Empresa::find($idEmpresa);
        if($empresa){
            $empresa->delete();
            return response()->json('Empresa eliminada con éxito.');
        }

        return response()->json('No se encontro la emppresa en la base de datos.');
    }

}
