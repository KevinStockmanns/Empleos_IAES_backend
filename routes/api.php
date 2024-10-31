<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('/v1/usuarios')->group(function() {
    Route::post("/login", [UsuarioController::class, 'loginUsuario']);
    Route::post("/", [UsuarioController::class, 'registrarUsuario']);
    Route::get("/{id}", [UsuarioController::class, 'obtenerUsuario']);
});