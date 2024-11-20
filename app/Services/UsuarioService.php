<?php

namespace App\Services;


use App\DTO\Usuario\UsuarioPerfilCompletoDTO;
use App\Enums\EstadoUsuarioEnum;
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
    private $contactoService;
    private $habilidadService;

    public function __construct(UbicacionService $ubicacionService,
        ContactoService $contactoService,
        HabilidadService $habilidadService
    ){
        $this->ubicacionService = $ubicacionService;
        $this->contactoService = $contactoService;
        $this->habilidadService = $habilidadService;
    }
    public function registrar(RegistrarUsuarioRequest $request, Usuario|null $admin)
    {
        $isAdmin = $admin !=null && $admin->isAdmin();
        $data = $request->validated();
        $rol = Rol::firstOrCreate([
            'nombre' => $data['rol'],
        ]);
        $direccion = null;
        if(isset($data['ubicacion'])){
            $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        }
        $usuario = new Usuario([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correo' => $data['correo'],
            'clave' => $isAdmin
                ? Hash::make($data['dni'])
                : Hash::make($data['clave']),
            'dni' => $data['dni'],
            'estado' => EstadoUsuarioEnum::SOLICITADO->value,
            'fecha_nacimiento' => isset($data['fechaNacimiento'])
                ? Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento'])
                : null,
            'direccion_id' => $direccion->id ?? null,
        ]);
        $usuario->rol_id = $rol->id;

        $usuario->save();

        $claims = [
            'rol' => $usuario->rol->nombre,
            'username' => $usuario->correo,
            'exp' => now()->addHours(4)->timestamp
        ];

        $token = JWTAuth::claims($claims)->fromUser($usuario);

        return [
            'usuario'=>$usuario,
            'token'=> $token
        ];
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

    public function cargarContacto($data, $idUsuario){
        $usuario = Usuario::find($idUsuario);
        if(!$usuario){
            throw new CustomException('No se encontro el usuario con el id '. $idUsuario, 404);
        }

        $contacto = $usuario->contacto;
        if($contacto){
            $contacto= $this->contactoService->actualizarContacto($contacto, $data['contacto']);
        }else{
            $contacto= $this->contactoService->crearContacto($data['contacto']);
            $usuario->contacto_id = $contacto->id;
            $usuario->save();
        }

        return $contacto;
    }

    public function cargarHabilidades($idUsuario, $data): array{
        $usuario = Usuario::find($idUsuario);
        if(!$usuario){
            throw new CustomException('No se econtro un usuario con el id ingresado', 404);
        }
        $habilidades = $this->habilidadService->buscarOCrear($data);

        $usuario->habilidades()->sync(
            collect($habilidades)->pluck('id')->toArray()
        );

        return $habilidades;
    }


    public function calcularPerfilCompletado($idUsuario){
        $usuario = $this->obtenerById($idUsuario);

        $datos = [
            ['Contacto', $usuario->contacto()->exists()],
            ['Información Profesional', $usuario->perfilProfesional()->exists()],
            ['Habilidades', $usuario->habilidades()->exists()],
            ['Ubicación',$usuario->direccion()->exists()],
        ];

        $datosTotales = count($datos);
        $datosCompletos = count(array_filter($datos, function($d){
            return $d[1];
        }));
        $porcentaje = $datosCompletos * 100 / $datosTotales;

        return new UsuarioPerfilCompletoDTO($porcentaje, $datos);
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