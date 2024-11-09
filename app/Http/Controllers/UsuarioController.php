<?php

namespace App\Http\Controllers;

use App\DTO\PerfilProfesional\PerfilProfesionalRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\UbicacionRequest;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioActualizarRequest;
use App\Http\Requests\Usuario\UsuarioCompletarRequest;
use App\Http\Requests\Usuario\UsuarioLoginRequest;
use App\Http\Requests\UsuarioPerfilProfesionalRequest;
use App\Services\UsuarioService;
use Auth;

class UsuarioController extends Controller
{
    protected $usuarioService;
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }


    public function registrarUsuario(RegistrarUsuarioRequest $request)
    {
        $usuario = $this->usuarioService->registrar($request);
        return response()->json(new UsuarioRespuestaDTO($usuario));
    }

    public function putUsuario(UsuarioActualizarRequest $req){
        $data = $req->validated();
        $id = $req->route('id');
        $usuario = $this->usuarioService->actualizarUsuario($id, $data);

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }

    public function obtenerUsuario($id)
    {
        // if(!auth()->check()){
        //     throw new CustomException('token requerido',403);
        // }
        $usuario = $this->usuarioService->obtenerById($id);

        if (!$usuario) {
            throw new CustomException('Usuario no encontrado en la base de datos', 404);
        }

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }


    public function loginUsuario(UsuarioLoginRequest $r){
        $data = $r->validated();

        $loginData = $this->usuarioService->login($data['username'], $data['clave']);

        return response()->json(new UsuarioRespuestaDTO($loginData['usuario'], $loginData['token']));
    }

    public function completarDatos(UsuarioCompletarRequest $r){
        $data = $r->validated();

        
    }

    public function postUbicacion(UbicacionRequest $r){
        $id = $r->route('id');
        $data =$r->validated();
        $direccion = $this->usuarioService->cargarUbicacion($id, $data);

        return response()->json(new UbicacionRespuestaDTO($direccion));
    }

    public function postPerfilProfesional(UsuarioPerfilProfesionalRequest $req){
        $data = $req->validated();
        $id = $req->route('id');
        $perfil = $this->usuarioService->cargarPerfilProfesional($id, $data['perfilProfesional']);

        return response()->json(new PerfilProfesionalRespuestaDTO($perfil));
    }

    
}
