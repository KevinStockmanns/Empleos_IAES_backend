<?php

namespace App\Enums;

enum EstadoUsuarioEnum: string{
    case PRIVADO = 'PRIVADO';
    case PUBLICO = 'PUBLICO';
    case SOLICITADO = "SOLICITADO";
    case BLOQUEADO = 'BLOQUEADO';
    case ALTA = 'ALTA';
}
