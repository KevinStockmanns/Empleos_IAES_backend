<?php

namespace App\Enums;

enum EstadoUsuarioEnum: string{
    case PRIVADO = 'PRIVADO';
    case PUBLICO = 'PUBLICO';
    case BLOQUEADO = 'BLOQUEADO';
    case ALTA = 'ALTA';
    case INCOMPLETO = 'INCOMPLETO';
}
