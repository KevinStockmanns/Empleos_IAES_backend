<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Direccion;
use App\Models\Empresa;
use App\Models\Horario;

class EmpresaService{
    private UbicacionService $ubicacionService;
    private HorarioService $horarioService;
    public function __construct(
        UbicacionService $ubicacionService,
        HorarioService $horarioService
    ){
        $this->ubicacionService = $ubicacionService;
        $this->horarioService = $horarioService;
    }
    public function registrarEmpresa($data): Empresa{
        $empresa =null;
        if (isset($data['cuil_cuit'])){
            $empresa= Empresa::where('cuil_cuit', $data['cuil_cuit'])
            ->firstOr(null);
        }

        if($empresa){
            return $empresa;
        }

        $empresa = new Empresa([
            'nombre'=>$data['nombre'],
            'cuil_cuit'=> isset($data['cuil_cuit'])?$data['cuil_cuit']:null,
            'referente'=> isset($data['referente'])?$data['referente']:null,
            'usuario_id'=>null,
            'direccion_id'=>null,
            'horario_id'=>null,
        ]);

        if(isset($data['ubicacion'])){
            $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
            $empresa->direccion_id = $direccion->id;
        }

        
        if(isset($data['horario'])){
            $horario = $this->horarioService->buscarORegistrar($data['horario']);
            $empresa->horario_id = $horario->id;
        }
        
        $empresa->save();
        return $empresa;
    }

    public function cambiarUbicacion($idEmpresa, $data): Direccion{
        $empresa = Empresa::find($idEmpresa);
        if(!$empresa){
            throw new CustomException("el usuario no se encontro en la base de datos", 404);
        }
        $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        $empresa->direccion_id = $direccion->id;
        $empresa->save();
        return $direccion;
    }
}