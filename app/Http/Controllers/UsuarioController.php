<?php

namespace App\Http\Controllers;

use App\DTO\Contacto\ContactoRespuestaDTO;
use App\DTO\Habilidad\HabilidadRespuestaDTO;
use App\DTO\PaginacionDTO;
use App\DTO\PerfilProfesional\PerfilProfesionalRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\DTO\Usuario\UsuarioDetalleDTO;
use App\DTO\Usuario\UsuarioListadoDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\Contacto\ContactoRequest;
use App\Http\Requests\Habilidad\HabilidadRegistrarRequest;
use App\Http\Requests\UbicacionRequest;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioActualizarRequest;
use App\Http\Requests\Usuario\UsuarioCompletarRequest;
use App\Http\Requests\Usuario\UsuarioDetalleRequest;
use App\Http\Requests\Usuario\UsuarioLoginRequest;
use App\Http\Requests\Usuario\UsuarioPerfilProfesionalRequest;
use App\Services\UsuarioService;
use Auth;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    protected $usuarioService;
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }


    public function registrarUsuario(RegistrarUsuarioRequest $request)
    {
        $admin = null;
        if (auth()->check()) {
            $admin = auth()->user();
        }
        $usuario = $this->usuarioService->registrar($request, $admin);
        return response()->json(new UsuarioRespuestaDTO($usuario['usuario'], $usuario['token']));
    }

    public function putUsuario(UsuarioActualizarRequest $req)
    {
        $data = $req->validated();
        $id = $req->route('id');
        $usuario = $this->usuarioService->actualizarUsuario($id, $data);

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }

    public function obtenerUsuario($id)
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $usuario = $this->usuarioService->obtenerById($id);

        if (!$usuario) {
            throw new CustomException('Usuario no encontrado en la base de datos', 404);
        }

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }


    public function listarUsuarios(Request $req)
    {
        $page = $req->get('page', 1);
        $size = $req->get('size', 15);
        $rol = $req->get('rol', RolEnum::ALUMNO->value);

        if (!in_array($rol, array_map(fn($role) => $role->value, RolEnum::cases()))) {
            $validRoles = implode(', ', array_map(fn($role) => $role->value, RolEnum::cases()));
            throw new CustomException('El rol enviado no es válido. Los roles válidos son: ' . $validRoles, 400);
        }

        if (!is_numeric($page) || !is_numeric($size)) {
            throw new CustomException('Los parametros deben ser númericos', 400);
        }
        $size = (int) $size;
        if ($size < 5 || $size > 15) {
            throw new CustomException('El parametro "size" debe ser entre 5 y 15', 400);
        }
        $page = (int) $page;
        if ($page <= 0) {
            throw new CustomException('el parametro "page" debe ser mayor a 0', 400);
        }

        $usuarios = $this->usuarioService->listarUsuarios($size, $page, $rol);

        $usuariosDTO = [];
        foreach ($usuarios->items() as $usuario) {
            $usuariosDTO[] = new UsuarioListadoDTO($usuario);
        }

        return response()->json(new PaginacionDTO(
            $usuariosDTO,
            $size,
            $page,
            $usuarios->lastPage(),
            $usuarios->total()
        ));
    }


    public function loginUsuario(UsuarioLoginRequest $r)
    {
        $data = $r->validated();

        $loginData = $this->usuarioService->login($data['username'], $data['clave']);

        return response()->json(new UsuarioRespuestaDTO($loginData['usuario'], $loginData['token']));
    }

    public function completarDatos(UsuarioCompletarRequest $r)
    {
        $data = $r->validated();


    }

    public function postUbicacion(UbicacionRequest $r)
    {
        $id = $r->route('id');
        $data = $r->validated();
        $direccion = $this->usuarioService->cargarUbicacion($id, $data);

        return response()->json(new UbicacionRespuestaDTO($direccion));
    }

    public function postPerfilProfesional(UsuarioPerfilProfesionalRequest $req)
    {
        $data = $req->validated();
        $id = $req->route('id');
        $perfil = $this->usuarioService->cargarPerfilProfesional($id, $data['perfilProfesional']);

        return response()->json(new PerfilProfesionalRespuestaDTO($perfil));
    }

    public function getRoles()
    {
        $roles = array_filter(
            RolEnum::cases(),
            fn($role) => $role->value !== "DEV"
        );

        return response()->json([
            "roles" => array_column($roles, 'value')
        ]);
    }
    
    public function postContacto(ContactoRequest $req){
        $data = $req->validated();
        $idUsuario = $req->route('id');
        $contacto = $this->usuarioService->cargarContacto($data, $idUsuario);

        return response()->json(new ContactoRespuestaDTO($contacto));
    }

    public function postHabilidades(HabilidadRegistrarRequest $req){
        $idUsuario = $req->route('id');
        $data = $req->validated();
        $habilidades = $this->usuarioService->cargarHabilidades($idUsuario, $data);

        return response()->json([
            'habilidades' => array_map(function($habilidad){
                return new HabilidadRespuestaDTO($habilidad);
            }, $habilidades)
        ]);
    
    }
    

    public function getDetalleUsuario(UsuarioDetalleRequest $request){
        $id = $request->validated()['id'];
        $usuario = $this->usuarioService->obtenerById($id);
        return response()->json(new UsuarioDetalleDTO($usuario));
    }

    public function getPerfilCompletado(Request $req){
        $idUsuario = $req->route('id');
        $data = $this->usuarioService->calcularPerfilCompletado($idUsuario);
        return response()->json($data);
    }
}
