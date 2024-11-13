<?php

namespace App\Services;


use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Models\PerfilProfesional;
use App\Models\Rol;
use App\Models\Usuario;
use Carbon\Carbon;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioService
{
    private $ubicacionService;

    public function __construct(UbicacionService $ubicacionService)
    {
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
            'direccion_id' => null,
        ]);
        $usuario->rol_id = $rol->id;

        $usuario->save();
        return $usuario;
    }

    public function obtenerById($id)
    {
        return Usuario::find($id);
    }

    public function login($username, $clave)
    {
        $usuario = Usuario::where('correo', $username)->first();
        if (!$usuario) {
            throw new CustomException('Credenciales inválidas', 403);
        }
        if (!$this->verifyPassword($clave, $usuario->clave)) {
            throw new CustomException('Credenciales inválidas', 403);
        }

        $claims = [
            'rol' => $usuario->rol->nombre,
            'username' => $usuario->correo,
            'exp' => now()->addHours(4)->timestamp
        ];

        $token = JWTAuth::claims($claims)->fromUser($usuario);

        return [
            'usuario' => $usuario,
            'token' => $token,
        ];
    }

    public function completarData($data)
    {
        if (isset($data['ubicacion'])) {

        }
    }

    public function cargarUbicacion($idUsuario, $data)
    {
        $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        $usuario = Usuario::find($idUsuario);

        if (!$usuario) {
            throw new CustomException('no se enontro el usuario con el id ingresado', 404);
        }
        $usuario->direccion_id = $direccion->id;
        $usuario->save();
        return $direccion;
    }
    public function actualizarUsuario($idUsuario, $data)
    {
        $usuario = Usuario::find($idUsuario);
        if (isset($data['nombre'])) {
            $usuario->nombre = $data['nombre'];
        }
        if (isset($data['apellido'])) {
            $usuario->apellido = $data['apellido'];
        }
        if (isset($data['correo'])) {
            $usuario->correo = $data['correo'];
        }
        if (isset($data['fechaNacimiento'])) {
            $usuario->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento']);
        }
        if (isset($data['dni'])) {
            $usuario->dni = $data['dni'];
        }
        if (isset($data['rol'])) {
            $rol = Rol::firstOrCreate([
                'nombre' => $data['rol']
            ]);
            $usuario->rol_id = $rol->id;
        }

        $usuario->save();
        return $usuario;
    }

    public function cargarPerfilProfesional($idUsuario, $data){
        $usuario = Usuario::find($idUsuario);
        if (!$usuario){
            throw new CustomException('no se econtro el ususario en la base de datos',404);
        }
        $perfilP = $usuario->perfilProfesional;
        if($perfilP){
            if(isset($data['cargo'])){
                $perfilP->cargo = $data['cargo'];
            }
            if(isset($data['cartaPresentacion'])){
                $perfilP->carta_presentacion = $data['cartaPresentacion'];
            }
            if(isset($data['disponibilidad'])){
                $perfilP->disponibilidad = $data['disponibilidad'];
            }
            if(isset($data['disponibilidadMudanza'])){
                $perfilP->disponibilidad_mudanza = $data['disponibilidadMudanza'];
            }
        }else{
            $perfilP = new PerfilProfesional([
                'cargo' => $data['cargo'],
                'carta_presentacion'=> $data['cartaPresentacion'] ?? null,
                'disponibilidad'=>$data['disponibilidad'],
                'disponibilidad_mudanza'=> $data['disponibilidadMudanza']
            ]);
            $perfilP->usuario_id = $usuario->id;
        }
        $perfilP->save();
        return $perfilP;
    }

    public function listarUsuarios($size, $page, $rol){
        $rolId = Rol::where('nombre', $rol)->first()->id;

        return Usuario::where('rol_id', $rolId)
            ->paginate($size, ['*'], 'page', $page);
    }



    public function encryptPassword($password)
    {
        return Hash::make($password);
    }

    public function verifyPassword($password, $hash)
    {
        return Hash::check($password, $hash);
    }

}