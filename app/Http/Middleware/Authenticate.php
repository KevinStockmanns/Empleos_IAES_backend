<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return "/login";
    // }

    // public function unauthenticated($request, array $guards){
    // return response()->json(['error' => 'Seguridad requerida'], 401);
    // }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            // Intenta obtener el usuario desde el token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Token no válido o usuario no encontrado'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token ha expirado'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token no proporcionado o no válido'], 401);
        }

        return $next($request);
    }

}
