<?php

namespace App\Http\Controllers;

use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioCompletarRequest;
use App\Http\Requests\Usuario\UsuarioLoginRequest;
use App\Services\UsuarioService;

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

    public function obtenerUsuario($id)
    {
        $usuario = $this->usuarioService->obtenerById($id);

        if (!$usuario) {
            throw new CustomException('Usuario no encontrado en la base de datos', 404);
        }

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }


    public function loginUsuario(UsuarioLoginRequest $r){
        $data = $r->validated();

        $usuario = $this->usuarioService->login($data['username'], $data['clave']);

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }

    public function completarDatos(UsuarioCompletarRequest $r){
        $data = $r->validated();

        
    }
}
