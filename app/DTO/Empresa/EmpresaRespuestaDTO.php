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
    public $horario;
    public $usuario_id;

    public function __construct(Empresa $empresa){
        $this->nombre = $empresa->nombre;
        $this->cuil_cuit = $empresa->cuil_cuit;
        $this->referente = $empresa->referente;
        $this->ubicacion = new UbicacionRespuestaDTO($empresa->direccion);
        $this->usuario_id = $empresa->usuario_id;
        $this->horario=new HorarioRespuestaDTO($empresa->horario);
    }
}