<?php

namespace App\Enums;

enum DisponibilidadEnum: string{
    case NO_DISPONIBLE = 'NO DISPONIBLE'; // No está buscando trabajo
    case DISPONIBLE = 'DISPONIBLE'; // Disponible para trabajar
    case EMPLEADO = 'EMPLEADO'; // Actualmente empleado
    case EN_BUSQUEDA = 'EN BÚSQUEDA'; // Buscando activamente trabajo
    case PASANTE = 'PASANTE'; // Actualmente en una pasantía
    case EN_ENTREVISTA = 'EN ENTREVISTA'; // En proceso de entrevistas
    case EN_PERIODO_DE_PRUEBA = 'EN PERÍODO DE PRUEBA'; // En periodo de prueba laboral
    case DISPONIBLE_PARCIAMENTE = 'DISPONIBLE PARCIALMENTE'; // Disponible para empleo a tiempo parcial
    case EMPLEO_ACTUAL_NO_RELEVANTE = 'EMPLEO ACTUAL NO RELEVANTE'; // Empleado, pero buscando otro tipo de trabajo
}
