<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrarUsuarioRequest;
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


    public function registrarUsuario(RegistrarUsuarioRequest $request){
        $usuario = $this->usuarioService->registrar($request);
        return response()->json($usuario);
    }
}
