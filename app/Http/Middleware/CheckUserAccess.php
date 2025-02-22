<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('id');
        $user = auth()->user();

        if(!$userId){
            throw new CustomException('El id del usuario es requerido.', 400);
        }
        if(!$user){
            throw new CustomException('El usuario no está autenticado.', 400);
        }

        if($userId == $user->id || $user->isAdmin()){
            return $next($request);
        }
        throw new CustomException('No tienes los permisos necesarios para continuar.', 400);
    }
}
