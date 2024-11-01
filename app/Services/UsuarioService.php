<?php

namespace App\Services;


use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Carbon\Carbon;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioService{
    private $ubicacionService;

    public function __construct(UbicacionService $ubicacionService){
        $this->ubicacionService = $ubicacionService;
    }
    public function registrar(RegistrarUsuarioRequest $request)
    {
        $data = $request->validated();
        $rol = Rol::firstOrCreate([
            'nombre' => isset($data['rol'])
                ? $data['rol']
                : RolEnum::ALUMNO->value,
        ]);
        $usuario = new Usuario([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correo' => $data['correo'],
            'clave' => $this->encryptPassword($data['clave']),
            'dni' => $data['dni'],
            'estado' => $data['estado'],
            'fecha_nacimiento' => isset($data['fechaNacimiento'])
                ? Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento'])
                : null,
            'direccion_id'=>null,
        ]);
        $usuario->rol_id = $rol->id;

        $usuario->save();
        return $usuario;
    }

    public function obtenerById($id)
    {
        return Usuario::find($id);
    }

    public function login($username, $clave){
        $usuario = Usuario::where('correo', $username)->first();

        if (!$usuario) {
            throw new CustomException('Credenciales inválidas', 403);
        }

        if (!$this->verifyPassword($clave, $usuario->clave)){
            throw new CustomException('Credenciales inválidas', 403);
        }

        return $usuario;
    }

    public function completarData($data){
        if(isset($data['ubicacion'])){
            
        }
    }

    public function cargarUbicacion($idUsuario, $data){
        $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        $usuario = Usuario::find($idUsuario);

        if(!$usuario){
            throw new CustomException('no se enontro el usuario con el id ingresado',404);
        }
        $usuario->direccion_id = $direccion->id;
        $usuario->save();
        return $direccion;
    }



    public function encryptPassword ($password){
        return Hash::make($password);
    }

    public function verifyPassword($password, $hash){
        return Hash::check($password, $hash);
    }

}