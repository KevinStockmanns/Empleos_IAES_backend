<?php
namespace App\Enums;

enum AccionCrudEnum:string{
    case ACTUALIZAR = 'ACTUALIZAR';
    case AGREGAR = 'AGREGAR';
    case ELIMINAR = 'ELIMINAR';
}