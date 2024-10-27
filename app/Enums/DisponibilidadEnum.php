<?php

namespace App\Enums;

enum DisponibilidadEnum: string{
    case NO_DISPONIBLE = 'NO_DISPONIBLE';
    case BUSQUEDA_ACTIVA = 'BUSQUEDA_ACTIVA';
    case TRABAJO_DISPONIBLE = 'TRABAJO_DISPONIBLE';
}
