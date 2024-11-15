<?php

namespace App\Http\Middleware;

use App\DTO\ErrorDTO;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json(new ErrorDTO('el token es requerido'), 401);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(new ErrorDTO('Token inválido'), 401);
            }
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException) {
                return response()->json(new ErrorDTO('El token ha expirado'), 401);
            } elseif ($e instanceof JWTException) {
                return response()->json(new ErrorDTO('Token inválido'), 401);
            }
        }

        return $next($request);
    }
}
