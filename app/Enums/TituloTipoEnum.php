<?php
namespace App\Enums;

enum TituloTipoEnum:string{
    case SECUNDARIO = 'SECUNDARIO';
    case TERCIARIO = 'TERCIARIO';
    case UNIVERSITARIO = 'UNIVERSITARIO';
    case CURSO = 'CURSO';
    case CAPACITACION = 'CAPACITACIÓN';
}