<?php

namespace App\Http\Controllers;

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
}
