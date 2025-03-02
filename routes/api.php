<?php

use App\Enums\CategoriaLicenciaConducirEnum;
use App\Enums\DisponibilidadEnum;
use App\Enums\EstadoCivilEnum;
use App\Enums\GeneroEnum;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HabilidadController;
use App\Http\Controllers\PasantiaController;
use App\Http\Controllers\UsuarioController;
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

    Route::middleware('jwt')->group(function () {
        Route::get("/", [UsuarioController::class, 'listarUsuarios']);
        Route::get("/roles", [UsuarioController::class, 'getRoles']);


        Route::get('/generos', function(){
            return response()->json(array_column(GeneroEnum::cases(), 'value'));
        });
        Route::get('/estado-civil', function(){
            return response()->json(array_column(EstadoCivilEnum::cases(), 'value'));
        });


        Route::get('/{id}/detalles', [UsuarioController::class, 'getDetalleUsuario']);
        Route::get('/{id}/completado', [UsuarioController::class, 'getPerfilCompletado']);
        Route::get("/{id}", [UsuarioController::class, 'obtenerUsuario']);
        Route::put("/{id}", [UsuarioController::class, 'putUsuario']);
        Route::post('/{id}/ubicacion', [UsuarioController::class, 'postUbicacion']);
        Route::post("/{id}/perfil_profesional", [UsuarioController::class, 'postPerfilProfesional']);
        Route::post('/{id}/contacto', [UsuarioController::class, 'postContacto']);
        Route::post('/{id}/habilidades', [UsuarioController::class, 'postHabilidades']);
        // Route::post('/{id}/foto_perfil', [UsuarioController::class, 'postPerfilImage']);

        Route::post('/{id}/imagen', [UsuarioController::class, 'postPerfilImage']);

        Route::post('/{id}/cv', [UsuarioController::class, 'postCV']);

        Route::post('{id}/titulo', [UsuarioController::class, 'postEducacion']);
        Route::post('/{id}/expLaboral', [UsuarioController::class, 'postExperienciaLaboral']);
        Route::post('/{id}/licenciaConducir', [UsuarioController::class, 'postLicenciaConducir'])
            ->middleware('user.access');


        
    });
    Route::get('/imagen/{image}', [UsuarioController::class, 'getFotoPerfil']);
    Route::get('/cv/{cv}', [UsuarioController::class, 'getCV']);
});

Route::prefix('/v1/empresas')->group(function(){

    Route::middleware('jwt')->group(function(){
        Route::get('', [EmpresaController::class,'listarEmpresas']);
        Route::get('/{id}', [EmpresaController::class,'getEmpresa']);


        Route::delete('/{idEmpresa}', [EmpresaController::class, 'deleteEmpresa']);
        
    });
    Route::post('/', [EmpresaController::class, 'postEmpresa']);
    

    Route::post('/{id}/ubicacion', [EmpresaController::class, 'cambiarUbicacion']);
});

Route::prefix('/v1/pasantias')->group(function(){
    Route::middleware('jwt')->group(function(){
        Route::get('/', [PasantiaController::class, 'listarPasantias']);
        Route::post('/', [PasantiaController::class, 'postPasantia']);
        Route::get('/{id}', [PasantiaController::class, 'getPasantia'])
            ->middleware('user.access');
    });
});

Route::prefix('/v1/habilidades')->group(function(){
    Route::middleware('jwt')->group(function(){
        Route::get('/', [HabilidadController::class, 'getHabilidades']);
    });
});

Route::prefix('/v1/disponiblidad')->group(function(){
    Route::middleware('jwt')->group(function(){
        Route::get('/', [HabilidadController::class, function(){
            return response()->json(array_column(DisponibilidadEnum::cases(), 'value'));
        }]);
    });
});

Route::prefix('/v1/licenciaConducir/categorias')->group(function(){
    Route::middleware('jwt')->group(function(){
        Route::get('/', function(){
            return response()->json(array_column(CategoriaLicenciaConducirEnum::cases(), 'value'));
        });
    });
});