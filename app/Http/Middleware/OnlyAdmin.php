<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if(!$user){
            throw new CustomException('No esta autenticado', 401);
        }

        if($user->isAdmin()){
            return $next($request);
        }

        throw new CustomException('No tienes los permisos necesarios para continuar.', 403);
    }
}
