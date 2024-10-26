<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrarUsuarioRequest;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    //
    public function miMetodo()
    {
        return response()->json([
            'mensaje' => 'Este es mi endpoint de la API',
            'status' => 'success'
        ], 200);
    }


    public function registrarUsuario(RegistrarUsuarioRequest $request){
        $data = $request->validated();
        return response()->json($data);
    }
}
