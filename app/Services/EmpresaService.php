<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Direccion;
use App\Models\Empresa;
use App\Models\Horario;
use App\Models\Usuario;
use DB;

class EmpresaService
{
    private UbicacionService $ubicacionService;
    private HorarioService $horarioService;
    public function __construct(
        UbicacionService $ubicacionService,
        HorarioService $horarioService
    ) {
        $this->ubicacionService = $ubicacionService;
        $this->horarioService = $horarioService;
    }
    public function registrarEmpresa($data) {
        DB::beginTransaction();
        try {
            $empresa = null;
            if (isset($data['cuil_cuit'])) {
                $empresa = Empresa::where('cuil_cuit', $data['cuil_cuit'])->first();
            }

            if ($empresa) {
                return $empresa;
            }
            $usuario = null;
            if(isset($data['idUsuario'])) {
                $usuario = Usuario::find($data['idUsuario']);
            }

            $empresa = Empresa::create([
                'nombre' => $data['nombre'],
                'cuil_cuit' => $data['cuil_cuit'] ?? null,
                'referente' => $data['referente'] ?? null,
                'usuario_id' => $usuario ? $usuario->id : null,
                'direccion_id' => null,
                'horario_id' => null,
            ]);

            if (isset($data['ubicacion'])) {
                $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
                $empresa->direccion_id = $direccion->id;
            }

            $empresa->save();

            if (isset($data['horarios']) && is_array($data['horarios'])) {
                $horariosIds = [];

                foreach ($data['horarios'] as $horarioData) {
                    $horario = $this->horarioService->buscarORegistrar($horarioData);
                    $horariosIds[] = $horario->id;
                }

                $empresa->horarios()->sync($horariosIds);
            }

            DB::commit();
            return $empresa;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomException('Ocurrio un error al crear la empresa', 500);
        }
    }

    public function cambiarUbicacion($idEmpresa, $data): Direccion
    {
        $empresa = Empresa::find($idEmpresa);
        if (!$empresa) {
            throw new CustomException("el usuario no se encontro en la base de datos", 404);
        }
        $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        $empresa->direccion_id = $direccion->id;
        $empresa->save();
        return $direccion;
    }
}