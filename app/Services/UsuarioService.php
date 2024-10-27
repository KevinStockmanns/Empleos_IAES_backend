<?php

namespace App\Services;


use App\Enums\RolEnum;
use App\Http\Requests\RegistrarUsuarioRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Carbon\Carbon;

class UsuarioService{
    public function registrar(RegistrarUsuarioRequest $request){
        $data = $request->validated();
        $rol = Rol::firstOrCreate([
            'nombre'=> RolEnum::ALUMNO->value,
        ]);
        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correo' => $data['correo'],
            'fecha_nacimiento' => Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento']),
            'clave' => $data['clave'],
            'dni' => $data['dni'],
            'rol_id' => $rol->id,
        ]);
        return $usuario;
    }
}