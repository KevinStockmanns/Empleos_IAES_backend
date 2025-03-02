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
        $authUser = auth()->user();
        if(!$authUser){
            throw new CustomException('El usuario no está autenticado.', 401);
        }

        if($authUser->isAdmin()){
            return $next($request);
        }

        $id = $request->route('id');
        $segment = $request->segment(3);

        if($segment == 'usuarios'){
            if($id == $authUser->id){
                return $next($request);
            }
        }elseif('pasantias'){
            if($authUser->pasantias()->where('id', $id)->exists()){
                return $next($request);
            }
        }


        logger($segment . " segment");

        throw new CustomException('No tienes los permisos necesarios para continuar.', 403);


    }
}
