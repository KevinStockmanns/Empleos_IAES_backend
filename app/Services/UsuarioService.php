<?php

namespace App\Services;


use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\RegistrarUsuarioRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioService
{
    private $encryptService;
    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }
    public function registrar(RegistrarUsuarioRequest $request)
    {
        $data = $request->validated();
        $rol = Rol::firstOrCreate([
            'nombre' => RolEnum::ALUMNO->value,
        ]);
        $usuario = new Usuario([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correo' => $data['correo'],
            'clave' => $this->encryptService->encryptPassword($data['clave']),
            'dni' => $data['dni'],
            'estado' => $data['estado'],
            'fecha_nacimiento' => isset($data['fechaNacimiento'])
                ? Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento'])
                : null,
        ]);
        $usuario->rol_id = $rol->id;

        $usuario->save();
        return $usuario;
    }

    public function obtenerById($id)
    {
        return Usuario::find($id);
    }

    public function login($credenciales){
        $usuario = Usuario::where('correo', $credenciales['username']);

        if (!$usuario || !$this->encryptService->verifyPassword($credenciales['clave'], $usuario->clave())) {
            throw new CustomException("Credenciales inválidas", 403);
        }

        try {
            if (!$token = JWTAuth::attempt($credenciales)) {
                throw new CustomException('ocurrio un error al inciar sesión', 500);
            }
        } catch (JWTException $e) {
            throw new CustomException('ocurrio un error al inciar sesión', 500);
        }

        return [
            'token' => $token,
            'usuario' => $usuario,
        ];
    }
}