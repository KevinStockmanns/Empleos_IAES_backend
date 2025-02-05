<?php
namespace App\DTO\Empresa;

use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Models\Empresa;

class EmpresaListadoDTO{
    public $id;
    public $nombre;
    public $referente;
    public $cuil_cuit;
    public $usuario;
    // public $ubicacion;

    public function __construct(Empresa $empresa){
        $direccion = $empresa->direccion;

        $this->id = $empresa->id;
        $this->nombre = $empresa->nombre;
        $this->referente = $empresa->referente;
        $this->cuil_cuit = $empresa->cuil_cuit;
        $this->usuario = $empresa->usuario ? new UsuarioRespuestaDTO($empresa->usuario) : null;
        // $this->ubicacion = $empresa->ubicacion ? new UbicacionRespuestaDTO($direccion) : null;
    }
}