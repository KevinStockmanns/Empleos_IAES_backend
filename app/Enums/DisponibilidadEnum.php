<?php

namespace App\Enums;

enum DisponibilidadEnum: string{
    case NO_DISPONIBLE = 'NO DISPONIBLE';
    case DISPONIBLE = 'DISPONIBLE';
    case EMPLEADO = 'EMPLEADO, EN BUSQUEDA';
}
