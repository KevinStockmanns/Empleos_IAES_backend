<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioLoginRequest;
use App\Services\UsuarioService;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    protected $usuarioService;
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public function miMetodo()
    {
        return response()->json([
            'mensaje' => 'Este es mi endpoint de la API',
            'status' => 'success'
        ], 200);
    }


    public function registrarUsuario(RegistrarUsuarioRequest $request)
    {
        $usuario = $this->usuarioService->registrar($request);
        return response()->json($usuario);
    }

    public function obtenerUsuario($id)
    {
        $usuario = $this->usuarioService->obtenerById($id);

        if (!$usuario) {
            throw new CustomException('Usuario no encontrado en la base de datos', 404);
        }

        return response()->json($usuario);
    }

    public function loginUsuario(UsuarioLoginRequest $request){
        $data = $request->validated();
        $credenciales = $request->only('username', 'clave');

        $result = $this->usuarioService->login($credenciales);
        return response()->json($result, 200);
    }
}
