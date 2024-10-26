<?php

namespace App\Enums;

enum RolEnum: string{
    case ALUMNO = 'ALUMNO';
    case ADMIN = 'ADMIN';
    case DEV = 'DEV';
    case EMPRESA = 'EMPRESA';
}
