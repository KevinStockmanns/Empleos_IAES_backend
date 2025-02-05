<?php
namespace App\DTO\Empresa;

use App\DTO\Horario\HorarioRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\Models\Empresa;

class EmpresaRespuestaDTO{
    public $nombre;
    public $cuil_cuit;
    public $referente;
    public $ubicacion;
    public $horarios;
    public $usuario_id;

    public function __construct(Empresa $empresa){
        $direccion = $empresa->direccion;
        $this->nombre = $empresa->nombre;
        $this->cuil_cuit = $empresa->cuil_cuit;
        $this->referente = $empresa->referente;
        $this->ubicacion = $direccion ? new UbicacionRespuestaDTO($empresa->direccion) : null;
        $this->usuario_id = $empresa->usuario_id;
        
        
        $horarios = $empresa->horarios;
        $this->horarios = $horarios 
            ? $horarios->map(function($horario) {
                return new HorarioRespuestaDTO($horario);
            })->toArray() 
            : [];
    }
}