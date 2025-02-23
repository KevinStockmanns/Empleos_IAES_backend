<?php

namespace App\Enums;

enum EstadoCivilEnum:string{
    case SOLTERO = 'SOLTERO(A)';
    case CASADO = 'CASADO(A)';
    case VIUDO = 'VIUDO(A)';
    case DIVORCIADO = 'DIVORCIADO(A)';
}