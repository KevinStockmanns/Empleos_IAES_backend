<?php

namespace App\Enums;

enum PasantiaEstadoEnum: string {
    case SOLICITADA = 'SOLICITADA';
    case APROBADA = 'APROBADA';
    case RECHAZADA = 'RECHAZADA';
}