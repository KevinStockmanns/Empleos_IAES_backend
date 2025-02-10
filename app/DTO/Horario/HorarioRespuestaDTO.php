<?php
namespace App\DTO\Horario;

use App\Models\Horario;

class HorarioRespuestaDTO
{
    public $desde;
    public $hasta;
    public $dias;


    public function __construct(Horario $horario)
    {
        $this->desde = $horario->desde->format('H:i');
        $this->hasta = $horario->hasta->format('H:i');
        $this->dias = $this->convertirDiasAArray($horario->dias);
        ;
    }

    private function convertirDiasAArray($dias)
    {
        $diasArray = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $diasTrabajo = $dias ? explode(',', $dias) : [];
        return array_map(function ($d) use ($diasArray) {
            return $diasArray[$d];
        }, $diasTrabajo);
    }
}